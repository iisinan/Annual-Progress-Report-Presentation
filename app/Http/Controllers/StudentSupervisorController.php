<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentSupervisorController extends Controller
{
    public function create()
    {
        $student = Auth::user()->student;
        $progName = strtolower($student->programme->name ?? '');
        $isPhd = str_contains($progName, 'phd') || str_contains($progName, 'doctor');
        $maxCount = $isPhd ? 3 : 2;

        if ($student->supervisors()->count() >= 1) {
            return redirect()->route('dashboard')->with('info', 'You have already assigned your supervisor(s).');
        }

        return view('student.supervisors.create', compact('maxCount'));
    }

    /**
     * AJAX endpoint: search existing supervisors by name or email.
     * Used by the live search autocomplete on the form.
     */
    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $results = User::role('Supervisor')
            ->where(function ($query) use ($q) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($q) . '%'])
                      ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($q) . '%']);
            })
            ->select('id', 'name', 'email')
            ->limit(10)
            ->get();

        return response()->json($results);
    }

    public function store(Request $request)
    {
        $student = Auth::user()->student;
        $progName = strtolower($student->programme->name ?? '');
        $isPhd = str_contains($progName, 'phd') || str_contains($progName, 'doctor');
        $maxCount = $isPhd ? 3 : 2;

        // Normalise + filter rows: skip rows where BOTH name and email are empty
        $rawSupervisors = $request->supervisors ?? [];
        $supervisors = [];

        foreach ($rawSupervisors as $sup) {
            $name  = trim($sup['name']  ?? '');
            $email = strtolower(trim($sup['email'] ?? ''));

            if ($name === '' && $email === '') {
                continue; // fully empty row – skip
            }

            $supervisors[] = ['name' => $name, 'email' => $email];
        }

        // Merge normalised data back so validation sees it
        $request->merge(['supervisors' => $supervisors]);

        $request->validate([
            'supervisors'          => ['required', 'array', 'min:1', "max:$maxCount"],
            'supervisors.*.name'   => 'required|string|max:255',
            'supervisors.*.email'  => 'required|email|max:255',
        ]);

        // Detect duplicate emails in the same submission
        $emails = array_column($supervisors, 'email');
        if (count($emails) !== count(array_unique($emails))) {
            return back()->withInput()->withErrors([
                'supervisors' => 'You have entered the same supervisor email address more than once. Each supervisor must have a unique email.',
            ]);
        }

        foreach ($supervisors as $supData) {
            $email = $supData['email'];   // already normalised (lowercase, trimmed)
            $name  = $supData['name'];

            // SMART: case-insensitive lookup first to avoid duplicates
            $existingUser = User::whereRaw('LOWER(email) = ?', [$email])->first();

            if ($existingUser) {
                // Reuse existing account – ensure Supervisor role
                $supervisorUser = $existingUser;
                if (!$supervisorUser->hasRole('Supervisor')) {
                    $supervisorUser->assignRole('Supervisor');
                }
            } else {
                // Create a new supervisor account
                $supervisorUser = User::create([
                    'name'              => $name,
                    'email'             => $email,
                    'password'          => Hash::make('password'),
                    'email_verified_at' => now(),
                ]);
                $supervisorUser->assignRole('Supervisor');
            }

            // Check if this supervisor is already linked to this specific student
            $alreadyLinked = $student->supervisors()
                ->where('users.id', $supervisorUser->id)
                ->exists();

            // Link supervisor → student (idempotent)
            $student->supervisors()->syncWithoutDetaching([
                $supervisorUser->id => ['status' => 'pending'],
            ]);

            // Send welcome email only for new links (not if re-assigned)
            if (!$alreadyLinked) {
                try {
                    $supervisorUser->notify(
                        new \App\Notifications\SupervisorAccountCreated($student, 'password')
                    );
                    Log::info('Supervisor welcome email sent to: ' . $supervisorUser->email);
                } catch (\Exception $e) {
                    Log::error('Supervisor welcome email FAILED for: ' . $supervisorUser->email . ' — ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('dashboard')
            ->with('success', 'Supervisors assigned successfully! They will receive an email invitation shortly.');
    }
}

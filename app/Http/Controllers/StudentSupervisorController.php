<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StudentSupervisorController extends Controller
{
    public function create()
    {
        $student = Auth::user()->student;
        $progName = strtolower($student->programme->name);
        $isPhd = str_contains($progName, 'phd') || str_contains($progName, 'doctor');
        $requiredCount = $isPhd ? 3 : 2;

        if ($student->supervisors()->count() >= $requiredCount) {
            return redirect()->route('dashboard')->with('info', 'You have already assigned your supervisors.');
        }

        return view('student.supervisors.create', compact('requiredCount'));
    }

    public function store(Request $request)
    {
        $student = Auth::user()->student;
        $progName = strtolower($student->programme->name);
        $isPhd = str_contains($progName, 'phd') || str_contains($progName, 'doctor');
        $requiredCount = $isPhd ? 3 : 2;

        $request->validate([
            'supervisors' => ['required', 'array', "size:$requiredCount"],
            'supervisors.*.name' => 'required|string|max:255',
            'supervisors.*.email' => 'required|email|max:255',
        ]);

        foreach ($request->supervisors as $supData) {
            // Check if this supervisor is already linked to this student BEFORE we create/attach
            $alreadyLinked = $student->supervisors()
                ->where('users.email', $supData['email'])
                ->exists();

            $supervisorUser = User::firstOrCreate(
                ['email' => $supData['email']],
                [
                    'name'              => $supData['name'],
                    'password'          => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            // Ensure they have the Supervisor role
            if (!$supervisorUser->hasRole('Supervisor')) {
                $supervisorUser->assignRole('Supervisor');
            }

            // Attach supervisor to student (pending status)
            $student->supervisors()->syncWithoutDetaching([
                $supervisorUser->id => ['status' => 'pending']
            ]);

            // Send the welcome/credentials email if this supervisor is newly assigned to this student
            // This handles both: brand new accounts AND existing accounts being linked for the first time
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

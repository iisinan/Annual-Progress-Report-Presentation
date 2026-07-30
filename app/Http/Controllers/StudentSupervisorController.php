<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            $isNewSupervisor = false;
            
            $supervisorUser = User::firstOrCreate(
                ['email' => $supData['email']],
                [
                    'name' => $supData['name'],
                    'password' => Hash::make('password'), // Default password
                    'email_verified_at' => now(),
                ]
            );

            if ($supervisorUser->wasRecentlyCreated) {
                $isNewSupervisor = true;
            }

            // Ensure they have the Supervisor role
            if (!$supervisorUser->hasRole('Supervisor')) {
                $supervisorUser->assignRole('Supervisor');
            }

            // Attach to student if not already attached
            $student->supervisors()->syncWithoutDetaching([
                $supervisorUser->id => ['status' => 'pending']
            ]);

            if ($isNewSupervisor) {
                $supervisorUser->notify(new \App\Notifications\SupervisorAccountCreated($student, 'password'));
            }
        }

        return redirect()->route('dashboard')->with('success', 'Supervisors assigned successfully.');
    }
}

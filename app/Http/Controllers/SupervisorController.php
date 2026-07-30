<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class SupervisorController extends Controller
{
    public function dashboard()
    {
        // Get all students assigned to this supervisor
        $students = Auth::user()->supervisees()->with('presentation')->get();
        
        $stats = [
            'total' => $students->count(),
            'pending' => $students->filter(fn($s) => $s->pivot->status === 'pending')->count(),
            'approved' => $students->filter(fn($s) => $s->pivot->status === 'approved')->count(),
            'scheduled' => $students->filter(fn($s) => $s->schedule !== null)->count(),
        ];

        return view('supervisor.dashboard', compact('students', 'stats'));
    }

    public function approve(Request $request, Student $student)
    {
        $supervisor = Auth::user();
        
        // Ensure this supervisor is assigned to this student
        if (!$supervisor->supervisees->contains($student->id)) {
            abort(403);
        }

        $supervisor->supervisees()->updateExistingPivot($student->id, [
            'status' => 'approved',
            'comments' => $request->input('comments')
        ]);

        if ($student->user) {
            $student->user->notify(new \App\Notifications\SupervisorReviewSubmitted($supervisor, 'approved', $request->input('comments')));
        }

        return back()->with('success', 'Presentation approved.');
    }

    public function reject(Request $request, Student $student)
    {
        $request->validate(['comments' => 'required|string']);
        
        $supervisor = Auth::user();
        
        if (!$supervisor->supervisees->contains($student->id)) {
            abort(403);
        }

        $supervisor->supervisees()->updateExistingPivot($student->id, [
            'status' => 'rejected',
            'comments' => $request->input('comments')
        ]);

        if ($student->user) {
            $student->user->notify(new \App\Notifications\SupervisorReviewSubmitted($supervisor, 'rejected', $request->input('comments')));
        }

        return back()->with('success', 'Presentation rejected.');
    }
}

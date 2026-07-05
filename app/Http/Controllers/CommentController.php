<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;

class CommentController extends Controller
{
    public function store(Request $request, Student $student)
    {
        $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $comment = Comment::create([
            'student_id' => $student->id,
            'user_id' => Auth::id(),
            'body' => $request->body,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Added Comment for Student: ' . $student->matric_number,
            'model_type' => 'Comment',
            'ip_address' => $request->ip()
        ]);

        return redirect()->back()->with('success', 'Comment added successfully.');
    }
}

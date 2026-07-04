<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Student $student)
    {
        $request->validate([
            'presentation_score' => 'required|integer|min:0|max:25',
            'research_content_score' => 'required|integer|min:0|max:25',
            'methodology_score' => 'required|integer|min:0|max:25',
            'qa_score' => 'required|integer|min:0|max:25',
            'remarks' => 'nullable|string',
        ]);

        if (!$student->schedule || $student->schedule->status !== 'presented') {
            return redirect()->back()->with('error', 'You cannot grade a student who has not presented yet.');
        }
        
        if (!$student->schedule->presentation_date->isToday()) {
            return redirect()->back()->with('error', 'You can only grade students scheduled for today.');
        }

        $totalScore = $request->presentation_score + $request->research_content_score + $request->methodology_score + $request->qa_score;

        Review::updateOrCreate(
            [
                'student_id' => $student->id,
                'examiner_id' => Auth::id(),
            ],
            [
                'presentation_score' => $request->presentation_score,
                'research_content_score' => $request->research_content_score,
                'methodology_score' => $request->methodology_score,
                'qa_score' => $request->qa_score,
                'total_score' => $totalScore,
                'remarks' => $request->remarks,
            ]
        );

        return redirect()->back()->with('success', 'Student grading submitted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Presentation;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\PresentationUploadedNotification;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function showUploadForm()
    {
        $student = Auth::user()->student;
        $presentation = $student->presentation;
        return view('student.upload', compact('presentation'));
    }

    public function uploadPresentation(Request $request)
    {
        $request->validate([
            'presentation_file' => 'required|file|mimes:pdf|mimetypes:application/pdf|max:102400', // 100MB, PDF only
        ]);

        $student = Auth::user()->student;
        $presentation = $student->presentation;

        // Ensure student can only upload once
        if ($presentation && $presentation->file_path) {
            return redirect()->route('dashboard')->with('error', 'You have already uploaded your presentation and cannot upload it again.');
        }

        $file = $request->file('presentation_file');
        
        $date = now()->format('Ymd_His');
        $fileName = "{$student->matric_number}_" . str_replace(' ', '', Auth::user()->name) . "_{$date}." . $file->getClientOriginalExtension();
        
        // Secure storage using Cloudflare R2
        $path = $file->storeAs('presentations', $fileName, 'r2');
        
        $presentation = $student->presentation;
        if (!$presentation) {
            $presentation = new Presentation(['student_id' => $student->id]);
        }
        
        $presentation->file_path = $path;
        $presentation->original_filename = $file->getClientOriginalName();
        $presentation->uploaded_at = now();
        $presentation->status = 'uploaded';
        $presentation->save();
        
        // Notify Student
        Auth::user()->notify(new PresentationUploadedNotification($presentation));
        
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Uploaded Presentation File: ' . $presentation->original_filename,
            'model_type' => 'Presentation',
            'model_id' => $presentation->id,
            'ip_address' => $request->ip()
        ]);
        
        return redirect()->route('dashboard')->with('success', 'Presentation uploaded successfully.');
    }

    public function downloadSlip()
    {
        $student = Auth::user()->student;
        $schedule = $student->schedule;
        
        $pdf = Pdf::loadView('pdf.acknowledgement_slip', compact('student', 'schedule'));
        return $pdf->download("Acknowledgement_Slip_{$student->matric_number}.pdf");
    }
}

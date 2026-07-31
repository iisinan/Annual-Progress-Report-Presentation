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
        $isResubmission = false;

        if ($presentation && $presentation->file_path) {
            Storage::disk('r2')->delete($presentation->file_path);
            $isResubmission = true;
        }

        $file = $request->file('presentation_file');
        
        $date = now()->format('Ymd_His');
        $fileName = "{$student->matric_number}_" . str_replace(' ', '', Auth::user()->name) . "_{$date}." . $file->getClientOriginalExtension();
        
        // Secure storage using Cloudflare R2
        $path = $file->storeAs('presentations', $fileName, 'r2');
        
        if (!$presentation) {
            $presentation = new Presentation(['student_id' => $student->id]);
        }
        
        $presentation->file_path = $path;
        $presentation->original_filename = $file->getClientOriginalName();
        $presentation->uploaded_at = now();
        $presentation->status = 'uploaded';
        $presentation->save();
        
        if ($isResubmission) {
            foreach ($student->supervisors as $supervisor) {
                $student->supervisors()->updateExistingPivot($supervisor->id, [
                    'status' => 'pending'
                ]);
            }
        }
        
        // Notify Student
        Auth::user()->notify(new PresentationUploadedNotification($presentation));

        // Notify Supervisors
        foreach ($student->supervisors as $supervisor) {
            $supervisor->notify(new \App\Notifications\PresentationReadyForReview($student));
        }
        
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Uploaded Presentation File: ' . $presentation->original_filename,
            'model_type' => 'Presentation',
            'model_id' => $presentation->id,
            'ip_address' => $request->ip()
        ]);
        
        return redirect()->route('dashboard')->with('success', 'Presentation uploaded successfully.');
    }

    public function deletePresentation()
    {
        $student = Auth::user()->student;
        $presentation = $student->presentation;

        if ($presentation && $presentation->file_path) {
            // Delete from storage
            Storage::disk('r2')->delete($presentation->file_path);
            
            // Update database
            $presentation->file_path = null;
            $presentation->original_filename = null;
            $presentation->uploaded_at = null;
            $presentation->status = 'pending';
            $presentation->save();

            // Reset supervisor approvals since the file is being resubmitted
            foreach ($student->supervisors as $supervisor) {
                $student->supervisors()->updateExistingPivot($supervisor->id, [
                    'status' => 'pending'
                ]);
            }

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'Deleted Presentation File for Resubmission',
                'model_type' => 'Presentation',
                'model_id' => $presentation->id,
                'ip_address' => request()->ip()
            ]);

            return redirect()->route('dashboard')->with('success', 'Presentation deleted successfully. You can now upload a new one.');
        }

        return redirect()->route('dashboard')->with('error', 'No presentation file found to delete.');
    }

    public function updateAbstract(Request $request)
    {
        $request->validate([
            'presentation_title' => 'required|string|max:10000',
        ]);

        $student = Auth::user()->student;
        $presentation = $student->presentation;
        
        if (!$presentation) {
            $presentation = new Presentation();
            $presentation->student_id = $student->id;
        }
        
        $presentation->presentation_title = $request->presentation_title;
        $presentation->save();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Updated Abstract',
            'model_type' => 'Presentation',
            'model_id' => $presentation->id,
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', 'Abstract updated successfully.');
    }

    public function downloadSlip()
    {
        $student = Auth::user()->student;
        $schedule = $student->schedule;
        
        $pdf = Pdf::loadView('pdf.acknowledgement_slip', compact('student', 'schedule'));
        return $pdf->download("Acknowledgement_Slip_{$student->matric_number}.pdf");
    }
}

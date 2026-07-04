<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presentation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;

class FileDownloadController extends Controller
{
    public function downloadPresentation(Presentation $presentation, Request $request)
    {
        // Check authorization: only Examiner or Admin can download, or the student themselves
        $user = Auth::user();
        if (!$user->hasRole('Administrator') && !$user->hasRole('Examiner') && (!isset($user->student) || $user->student->id !== $presentation->student_id)) {
            abort(403, 'Unauthorized access to this file.');
        }

        if (!Storage::disk('r2')->exists($presentation->file_path)) {
            // Fallback for files that might still be on local private disk before migration
            if (Storage::disk('private')->exists($presentation->file_path)) {
                return Storage::disk('private')->download($presentation->file_path, $presentation->original_filename);
            }
            abort(404, 'File not found on the server or Cloudflare R2.');
        }

        // Log download
        if ($user->hasRole('Examiner')) {
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'Examiner Downloaded Presentation: ' . $presentation->original_filename,
                'model_type' => 'Presentation',
                'model_id' => $presentation->id,
                'ip_address' => $request->ip()
            ]);
        }

        return Storage::disk('r2')->download($presentation->file_path, $presentation->original_filename);
    }
}

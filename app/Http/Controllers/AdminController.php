<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Models\AuditLog;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    public function students(Request $request)
    {
        $currentSession = SystemSetting::where('key', 'current_session')->value('value') ?? '2025/2026';
        $session = $request->query('session', $currentSession);

        $query = Student::with(['user', 'programme', 'department', 'presentation', 'schedule']);
        
        if ($session !== 'all') {
            $query->where('academic_session', $session);
        }

        $students = $query->latest()->get();
        $sessions = Student::select('academic_session')->distinct()->pluck('academic_session');

        return view('admin.students', compact('students', 'currentSession', 'session', 'sessions'));
    }

    public function showStudent(Student $student)
    {
        $student->load(['user', 'programme', 'department', 'presentation', 'schedule', 'reviews.examiner']);
        return view('admin.students.show', compact('student'));
    }

    public function destroyStudent(Student $student)
    {
        $name = $student->user->name;
        $student->user->delete(); // This cascades to the student record due to our migration

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Deleted Student: ' . $name,
            'model_type' => 'Student',
            'ip_address' => request()->ip()
        ]);

        return redirect()->back()->with('success', 'Student deleted successfully.');
    }

    public function examiners()
    {
        $examiners = User::role('Examiner')->latest()->get();
        return view('admin.examiners', compact('examiners'));
    }

    public function storeExaminer(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('Examiner');

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Created New Examiner: ' . $user->email,
            'model_type' => 'User',
            'ip_address' => $request->ip()
        ]);

        return redirect()->back()->with('success', 'Examiner added successfully!');
    }

    public function destroyExaminer(User $examiner)
    {
        $name = $examiner->name;
        // Verify they are actually an examiner
        if ($examiner->hasRole('Examiner')) {
            $examiner->delete();
            
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'Deleted Examiner: ' . $name,
                'model_type' => 'User',
                'ip_address' => request()->ip()
            ]);

            return redirect()->back()->with('success', 'Examiner deleted successfully.');
        }
        
        return redirect()->back()->with('error', 'User is not an examiner.');
    }

    public function settings()
    {
        $settings = SystemSetting::all()->pluck('value', 'key');
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $allowedKeys = [
            'academic_session', 'registration_open_date', 'registration_close_date',
            'presentation_start_date', 'presentation_end_date', 'students_per_day',
            'presentation_start_time', 'presentation_duration',
            'is_upload_active', 'is_registration_active'
        ];
        
        $inputs = $request->only($allowedKeys);
        
        // Remove nulls so we don't accidentally blank out values if missing from request
        $inputs = array_filter($inputs, function($value) {
            return $value !== null;
        });

        foreach ($inputs as $key => $value) {
            SystemSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Updated System Settings',
            'model_type' => 'SystemSetting',
            'ip_address' => $request->ip()
        ]);
        
        return redirect()->back()->with('success', 'System settings updated successfully.');
    }

    public function auditLogs()
    {
        $logs = AuditLog::with('user')->latest()->paginate(50);
        return view('admin.audit-logs', compact('logs'));
    }
}

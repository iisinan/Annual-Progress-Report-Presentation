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
use App\Models\Presentation;
use Illuminate\Support\Facades\DB;
use App\Models\Department;
use App\Models\Programme;
use App\Notifications\MassEmailNotification;
use Illuminate\Support\Facades\Notification;

class AdminController extends Controller
{
    public function students(Request $request)
    {
        $currentSession = SystemSetting::where('key', 'academic_session')->value('value') ?? '2025/2026';
        $session = $request->query('session', $currentSession);

        $query = Student::with(['user', 'programme', 'department', 'presentation', 'schedule']);
        
        if ($session !== 'all') {
            $query->where('academic_session', $session);
        }

        $students = $query->latest()->get();
        $sessions = Student::select('academic_session')->distinct()->pluck('academic_session');
        $departments = Department::all();
        $programmes = Programme::all();

        return view('admin.students', compact('students', 'currentSession', 'session', 'sessions', 'departments', 'programmes'));
    }

    public function showStudent(Student $student)
    {
        $student->load(['user', 'programme', 'department', 'presentation', 'schedule', 'reviews.examiner']);
        return view('admin.students.show', compact('student'));
    }

    public function sendMassEmail(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $students = User::role('Student')->get();

        Notification::send($students, new MassEmailNotification($request->subject, $request->message));

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Mass Email Sent',
            'description' => "Sent mass email to " . $students->count() . " students. Subject: " . $request->subject,
        ]);

        return back()->with('success', 'Email sent to all students successfully.');
    }

    public function destroyStudent(Student $student)
    {
        $name = $student->user ? $student->user->name : 'Unknown Student';
        
        // Wipe out related data
        if ($student->presentation) {
            $student->presentation()->forceDelete();
        }
        if ($student->schedule) {
            $student->schedule()->forceDelete();
        }
        $student->reviews()->forceDelete();
        $student->comments()->forceDelete();

        $user = $student->user;
        
        // Force delete the student
        $student->forceDelete(); 

        if ($user) {
            // Force delete the user to allow re-registration with same email
            $user->forceDelete(); 
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Deleted Student: ' . $name,
            'model_type' => 'Student',
            'ip_address' => request()->ip()
        ]);

        return redirect()->back()->with('success', 'Student deleted successfully.');
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'matric_number' => ['required', 'string', 'max:255', 'unique:students,matric_number'],
            'phone_number' => ['required', 'string', 'max:20', 'unique:students,phone_number'],
            'department_id' => ['required', 'exists:departments,id'],
            'programme_id' => ['required', 'exists:programmes,id'],
            'academic_session' => ['required', 'string', 'max:255'],
            'year_of_admission' => ['required', 'integer', 'min:2010', 'max:' . date('Y')],
            'intake' => ['required', 'integer', 'in:1,2'],
            'supervisor_name' => ['required', 'string', 'max:255'],
            'research_title' => ['required', 'string', 'max:500'],
            'presentation_title' => ['required', 'string', 'max:500'],
            'current_research_stage' => ['required', 'string', 'max:255'],
        ]);

        $student = null;
        DB::transaction(function () use ($request, &$student) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole('Student');

            $student = Student::create([
                'user_id' => $user->id,
                'matric_number' => $request->matric_number,
                'academic_session' => $request->academic_session,
                'year_of_admission' => $request->year_of_admission,
                'intake' => $request->intake,
                'phone_number' => $request->phone_number,
                'department_id' => $request->department_id,
                'programme_id' => $request->programme_id,
                'supervisor_name' => $request->supervisor_name,
                'research_title' => $request->research_title,
                'current_research_stage' => $request->current_research_stage,
            ]);

            Presentation::create([
                'student_id' => $student->id,
                'presentation_title' => $request->presentation_title,
                'status' => 'pending',
            ]);
        });

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Created New Student: ' . $request->matric_number,
            'model_type' => 'Student',
            'ip_address' => $request->ip()
        ]);

        return redirect()->back()->with('success', 'Student account created successfully!');
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

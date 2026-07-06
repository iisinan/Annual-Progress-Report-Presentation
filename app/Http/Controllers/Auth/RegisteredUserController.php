<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Presentation;
use App\Models\Department;
use App\Models\Programme;
use App\Models\SystemSetting;
use App\Notifications\StudentRegisteredNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View|\Illuminate\Http\RedirectResponse
    {
        $isRegistrationActive = SystemSetting::where('key', 'is_registration_active')->value('value') ?? '1';
        if ($isRegistrationActive == '0') {
            return redirect()->route('login')->with('error', 'Student Registration is currently deactivated by the administrator.');
        }

        $departments = Department::all();
        $programmes = Programme::all();
        
        return view('auth.register', compact('departments', 'programmes'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $isRegistrationActive = SystemSetting::where('key', 'is_registration_active')->value('value') ?? '1';
        if ($isRegistrationActive == '0') {
            return redirect()->route('login')->with('error', 'Student Registration is currently deactivated by the administrator.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'matric_number' => ['required', 'string', 'max:255', 'unique:students,matric_number'],
            'phone_number' => ['required', 'string', 'max:20', 'unique:students,phone_number'],
            'department_id' => ['required', 'exists:departments,id'],
            'programme_id' => ['required', 'exists:programmes,id'],
            'year_of_admission' => ['required', 'integer', 'min:2010', 'max:' . date('Y')],
            'intake' => ['required', 'integer', 'in:1,2'],
            'supervisor_name' => ['required', 'string', 'max:255'],
            'research_title' => ['required', 'string', 'max:500'],
            'presentation_title' => ['required', 'string', 'max:500'],
            'current_research_stage' => ['required', 'string', 'max:255'],
            'confirm_info' => ['accepted'],
        ]);

        $student = null;
        DB::transaction(function () use ($request, &$user, &$student) {
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
                'academic_session' => SystemSetting::where('key', 'academic_session')->value('value') ?? '2025/2026',
            ]);

            Presentation::create([
                'student_id' => $student->id,
                'presentation_title' => $request->presentation_title,
                'status' => 'pending',
            ]);
        });

        event(new Registered($user));

        // Notify Student
        $user->notify(new StudentRegisteredNotification($student));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}

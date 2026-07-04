<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\SystemSetting;
use App\Models\AuditLog;
use App\Notifications\ScheduleGeneratedNotification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with('student.user')->orderBy('presentation_date')->orderBy('start_time')->get();
        return view('admin.schedule', compact('schedules'));
    }

    public function generate(Request $request)
    {
        $startDateStr = $request->input('start_date', SystemSetting::where('key', 'presentation_start_date')->value('value') ?? '2026-07-27');
        $studentsPerDay = (int) $request->input('students_per_day', SystemSetting::where('key', 'students_per_day')->value('value') ?? 5);
        $startTimeStr = $request->input('start_time', SystemSetting::where('key', 'presentation_start_time')->value('value') ?? '09:00');
        $duration = (int) $request->input('duration', SystemSetting::where('key', 'presentation_duration')->value('value') ?? 30);
        $breakDuration = (int) $request->input('break_duration', SystemSetting::where('key', 'break_duration')->value('value') ?? 0);
        $venue = $request->input('venue', SystemSetting::where('key', 'venue')->value('value') ?? 'Main Hall');

        $students = Student::whereDoesntHave('schedule')->get();

        if ($students->isEmpty()) {
            return redirect()->back()->with('error', 'All registered students already have a schedule.');
        }

        $currentDate = Carbon::parse($startDateStr);
        // Ensure starting date is not a weekend
        if ($currentDate->isWeekend()) {
            $currentDate->addDays($currentDate->isSaturday() ? 2 : 1);
        }
        $currentTime = Carbon::parse($startTimeStr);
        $dailyCount = 0;

        foreach ($students as $student) {
            if ($dailyCount >= $studentsPerDay) {
                // Move to next day
                $currentDate->addDay();
                // Skip weekends (optional, but standard for academics)
                if ($currentDate->isWeekend()) {
                    $currentDate->addDays($currentDate->isSaturday() ? 2 : 1);
                }
                $currentTime = Carbon::parse($startTimeStr);
                $dailyCount = 0;
            }

            $endTime = (clone $currentTime)->addMinutes($duration);

            $schedule = Schedule::create([
                'student_id' => $student->id,
                'presentation_date' => $currentDate->format('Y-m-d'),
                'start_time' => $currentTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s'),
                'venue' => $venue,
                'status' => 'scheduled'
            ]);
            
            // Notify Student
            if ($student->user) {
                $student->user->notify(new ScheduleGeneratedNotification($schedule));
            }

            // Setup for next slot
            $currentTime = $endTime->addMinutes($breakDuration);
            $dailyCount++;
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Generated Presentation Schedule for ' . $students->count() . ' students',
            'model_type' => 'Schedule',
            'ip_address' => $request->ip()
        ]);

        return redirect()->back()->with('success', 'Presentation schedule generated successfully for ' . $students->count() . ' students.');
    }

    public function clear(Request $request)
    {
        $count = Schedule::count();
        Schedule::query()->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => "Cleared entire schedule ($count entries)",
            'model_type' => 'Schedule',
            'ip_address' => $request->ip()
        ]);

        return redirect()->back()->with('success', 'The schedule has been completely cleared.');
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'presentation_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'venue' => 'required|string|max:255',
            'status' => 'required|string|in:scheduled,presented,missed',
        ]);

        $schedule->update([
            'presentation_date' => $request->presentation_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'venue' => $request->venue,
            'status' => $request->status,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => "Updated schedule for student: " . $schedule->student->matric_number,
            'model_type' => 'Schedule',
            'ip_address' => $request->ip()
        ]);

        return redirect()->back()->with('success', 'Schedule updated successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Student;
use App\Models\Presentation;
use App\Models\Schedule;
use App\Models\SystemSetting;
use App\Models\Announcement;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Administrator')) {
            $stats = [
                'total_students' => Student::count(),
                'registered_students' => Student::whereYear('created_at', date('Y'))->count(),
                'uploaded_presentations' => Presentation::whereNotNull('file_path')->count(),
                'pending_uploads' => Student::whereDoesntHave('presentation', fn($q) => $q->whereNotNull('file_path'))->count(),
                'total_examiners' => User::role('Examiner')->count(),
                'presentation_days' => Schedule::select('presentation_date')->distinct()->count(),
                'graded_presentations' => Student::has('reviews')->count(),
                'pending_evaluations' => Schedule::where('status', 'presented')->whereDoesntHave('student.reviews')->count(),
            ];
            
            // Chart Data
            $months = collect(range(1, 12))->map(function ($month) {
                return date('M', mktime(0, 0, 0, $month, 1));
            });
            
            // SQLite compatible grouping
            $registrations = Student::whereYear('created_at', date('Y'))->get();
            $registrationsByMonth = $registrations->groupBy(function($date) {
                return (int) $date->created_at->format('m');
            })->map(function ($group) {
                return $group->count();
            })->toArray();
            
            $registrationData = [];
            foreach (range(1, 12) as $m) {
                $registrationData[] = $registrationsByMonth[$m] ?? 0;
            }

            $departmentStats = Student::selectRaw('department_id, count(*) as count')
                                      ->with('department:id,name')
                                      ->groupBy('department_id')
                                      ->get()
                                      ->map(function ($stat) {
                                          return [
                                              'name' => $stat->department ? $stat->department->name : 'Unknown',
                                              'count' => $stat->count
                                          ];
                                      });
                                      
            $departmentLabels = $departmentStats->pluck('name')->toArray();
            $departmentData = $departmentStats->pluck('count')->toArray();

            return view('dashboard.admin', compact('stats', 'months', 'registrationData', 'departmentLabels', 'departmentData'));
        }

        if ($user->hasRole('Student')) {
            $student = $user->student;
            $presentation = $student->presentation;
            $schedule = $student->schedule;
            
            $status = [
                'registration' => 'Completed',
                'powerpoint' => $presentation && $presentation->file_path ? 'Uploaded' : 'Pending',
                'presentation_date' => $schedule ? $schedule->presentation_date->format('d M Y') : 'Not scheduled',
                'presentation_time' => $schedule ? $schedule->start_time->format('h:i A') : 'N/A',
                'venue' => $schedule ? $schedule->venue : 'N/A',
                'is_presented' => $schedule && $schedule->status === 'presented',
            ];
            
            $results = null;
            if ($status['is_presented'] && $student->reviews()->exists()) {
                $reviews = $student->reviews;
                $results = [
                    'presentation_score' => round($reviews->avg('presentation_score'), 1),
                    'research_content_score' => round($reviews->avg('research_content_score'), 1),
                    'methodology_score' => round($reviews->avg('methodology_score'), 1),
                    'qa_score' => round($reviews->avg('qa_score'), 1),
                    'total_score' => round($reviews->avg('total_score'), 1),
                    'remarks' => $reviews->pluck('remarks')->filter()->implode(' | '),
                ];
            }
            
            $announcements = Announcement::where('is_active', true)->latest()->take(3)->get();
            
            return view('dashboard.student', compact('student', 'presentation', 'schedule', 'status', 'results', 'announcements'));
        }

        if ($user->hasRole('Examiner')) {
            $stats = [
                'registered_students' => Student::count(),
                'uploaded_presentations' => Presentation::whereNotNull('file_path')->count(),
                'pending_uploads' => Student::whereDoesntHave('presentation', fn($q) => $q->whereNotNull('file_path'))->count(),
                'todays_presentations' => Schedule::whereDate('presentation_date', today())->count(),
            ];
            $todays_schedule = Schedule::with(['student.user', 'student.programme', 'student.presentation', 'student.reviews' => function($q) use ($user) {
                $q->where('examiner_id', $user->id);
            }])->whereDate('presentation_date', today())->get();
            
            $announcements = Announcement::where('is_active', true)->latest()->take(3)->get();
            
            return view('dashboard.examiner', compact('stats', 'todays_schedule', 'announcements'));
        }
        
        abort(403);
    }

    public function markNotificationsAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }
}

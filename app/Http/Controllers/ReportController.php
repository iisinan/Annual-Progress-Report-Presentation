<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\AuditLog;
use App\Exports\StudentsExport;
use App\Exports\ScheduleExport;
use App\Exports\GradesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function exportGradesExcel()
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Exported Master Grades (Excel)',
            'model_type' => 'Report',
            'ip_address' => request()->ip()
        ]);

        return Excel::download(new GradesExport, 'master_grades_' . date('Y-m-d') . '.xlsx');
    }

    public function exportStudentsExcel()
    {
        return Excel::download(new StudentsExport, 'ACETEL_Registered_Students_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function exportStudentsPdf()
    {
        $students = Student::with(['user', 'programme', 'department', 'presentation'])->get();
        $pdf = Pdf::loadView('pdf.reports.students', compact('students'))
                  ->setPaper('a4', 'landscape');
        
        return $pdf->download('ACETEL_Registered_Students_' . now()->format('Ymd_His') . '.pdf');
    }

    public function exportSchedulePdf()
    {
        $schedules = Schedule::with(['student.user', 'student.programme'])->orderBy('presentation_date')->orderBy('start_time')->get();
        
        // Group by Date for better display
        $groupedSchedules = $schedules->groupBy(function($item) {
            return $item->presentation_date->format('Y-m-d');
        });

        $pdf = Pdf::loadView('pdf.reports.schedule', compact('groupedSchedules'))
                  ->setPaper('a4', 'portrait');
        
        return $pdf->download('ACETEL_Presentation_Schedule_' . now()->format('Ymd_His') . '.pdf');
    }
}

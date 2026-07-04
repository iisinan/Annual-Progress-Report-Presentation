<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return Student::with(['user', 'programme', 'department', 'presentation'])->get();
    }

    public function headings(): array
    {
        return [
            'S/N',
            'Matric Number',
            'Full Name',
            'Email Address',
            'Programme',
            'Department',
            'Supervisor',
            'Research Title',
            'Stage',
            'PPT Status',
            'Registered At'
        ];
    }

    public function map($student): array
    {
        static $row = 1;
        return [
            $row++,
            $student->matric_number,
            $student->user->name,
            $student->user->email,
            $student->programme->name,
            $student->department->name,
            $student->supervisor_name,
            $student->research_title,
            $student->current_research_stage,
            $student->presentation && $student->presentation->file_path ? 'Uploaded' : 'Pending',
            $student->created_at->format('Y-m-d H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FF0B3D91']]],
        ];
    }
}

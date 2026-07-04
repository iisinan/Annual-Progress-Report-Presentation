<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GradesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        // Only get students who have been graded
        return Student::with(['user', 'department', 'programme', 'reviews.examiner'])
            ->whereHas('reviews')
            ->get();
    }

    public function headings(): array
    {
        return [
            'S/N',
            'Matric Number',
            'Name',
            'Programme',
            'Department',
            'Research Title',
            'Examiner',
            'Presentation (20)',
            'Content (30)',
            'Methodology (30)',
            'Q&A (20)',
            'Total Score (100)',
            'Remarks',
        ];
    }

    public function map($student): array
    {
        // Calculate averages across multiple reviews if necessary
        $presentation = round($student->reviews->avg('presentation_score'), 1);
        $content = round($student->reviews->avg('research_content_score'), 1);
        $methodology = round($student->reviews->avg('methodology_score'), 1);
        $qa = round($student->reviews->avg('qa_score'), 1);
        $total = round($student->reviews->avg('total_score'), 1);
        
        $examiners = $student->reviews->map(function($review) {
            return $review->examiner->name;
        })->unique()->implode(', ');

        $remarks = $student->reviews->pluck('remarks')->filter()->implode(' | ');

        return [
            $student->id,
            $student->matric_number,
            $student->user->name,
            $student->programme->name,
            $student->department->name,
            $student->research_title,
            $examiners,
            $presentation,
            $content,
            $methodology,
            $qa,
            $total,
            $remarks,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

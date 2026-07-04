<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'student_id',
        'examiner_id',
        'presentation_score',
        'research_content_score',
        'methodology_score',
        'qa_score',
        'total_score',
        'remarks'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function examiner()
    {
        return $this->belongsTo(User::class, 'examiner_id');
    }
}

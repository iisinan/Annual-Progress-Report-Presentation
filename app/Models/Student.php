<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'programme_id',
        'department_id',
        'matric_number',
        'academic_session',
        'year_of_admission',
        'intake',
        'phone_number',
        'supervisor_name',
        'research_title',
        'current_research_stage',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }

    public function presentation(): HasOne
    {
        return $this->hasOne(Presentation::class);
    }

    public function schedule()
    {
        return $this->hasOne(Schedule::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function getDurationInfoAttribute()
    {
        if (!$this->year_of_admission || !$this->intake) return null;
        
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');
        $currentIntake = $currentMonth >= 7 ? 2 : 1; 
        
        $yearsElapsed = $currentYear - $this->year_of_admission;
        
        // Semesters elapsed: 2 semesters per year
        $semestersElapsed = ($yearsElapsed * 2) + ($currentIntake - $this->intake);
        
        // Minimum required (MSc = 3, PhD = 6)
        $progName = strtolower($this->programme->name ?? '');
        $minSemesters = (str_contains($progName, 'phd') || str_contains($progName, 'doctor')) ? 6 : 3;
        
        $status = 'In Progress';
        if ($semestersElapsed >= $minSemesters) {
            $status = 'Eligible to Graduate';
            if ($semestersElapsed > $minSemesters + 2) {
                 $status = 'Overstayed';
            }
        }
        
        return [
            'semesters_spent' => max(0, $semestersElapsed),
            'min_required' => $minSemesters,
            'status' => $status,
        ];
    }
}

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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['course_id', 'title', 'description', 'video_type', 'video_url', 'release_date', 'order'])]
class Lesson extends Model
{
    use HasFactory;

    protected $casts = [
        'release_date' => 'datetime',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function completions(): HasMany
    {
        return $this->hasMany(LessonCompletion::class);
    }

    public function assessment(): HasOne
    {
        return $this->hasOne(LessonAssessment::class);
    }

    public function scopeReleased($query)
    {
        return $query->where('release_date', '<=', now());
    }
}

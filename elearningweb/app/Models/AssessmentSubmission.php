<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['lesson_assessment_id', 'user_id', 'submission_type', 'response', 'file_path', 'status', 'feedback'])]
class AssessmentSubmission extends Model
{
    use HasFactory;

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(LessonAssessment::class, 'lesson_assessment_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'course_id', 'price', 'paid', 'payment_status', 'payment_provider', 'payment_intent_id', 'trial_ends_at', 'grace_ends_at'])]
class Enrollment extends Model
{
    use HasFactory;

    protected $casts = [
        'price' => 'decimal:2',
        'paid' => 'boolean',
        'trial_ends_at' => 'datetime',
        'grace_ends_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getTrialRemainingDaysAttribute(): int
    {
        return now()->diffInDays($this->trial_ends_at, false);
    }

    public function getGraceRemainingDaysAttribute(): int
    {
        return now()->diffInDays($this->grace_ends_at, false);
    }

    public function hasAccess(): bool
    {
        if ($this->paid && $this->payment_status === 'succeeded') {
            return true;
        }

        return now()->isBefore($this->trial_ends_at) || ($this->grace_ends_at !== null && now()->isBefore($this->grace_ends_at));
    }

    public function isPaymentPending(): bool
    {
        return $this->payments()->where('status', 'pending')->exists();
    }
}

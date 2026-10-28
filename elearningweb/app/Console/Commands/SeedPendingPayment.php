<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SeedPendingPayment extends Command
{
    protected $signature = 'admin:seed-pending-payment {--student_id=} {--course_id=}';

    protected $description = 'Create a pending payment for testing (first student + course by default).';

    public function handle(): int
    {
        $studentId = $this->option('student_id');
        $courseId = $this->option('course_id');

        $student = $studentId ? User::find($studentId) : User::where('role', 'student')->first();
        if (! $student) {
            $this->error('No student user found.');
            return 1;
        }

        $course = $courseId ? Course::find($courseId) : Course::published()->first();
        if (! $course) {
            $this->error('No published course found.');
            return 1;
        }

        $enrollment = Enrollment::firstOrCreate(
            ['user_id' => $student->id, 'course_id' => $course->id],
            [
                'price' => $course->price,
                'paid' => false,
                'payment_status' => 'pending',
                'trial_ends_at' => now()->addDays(8),
                'grace_ends_at' => now()->addDays(8)->addHours(12),
            ]
        );

        $payment = $enrollment->payments()->create([
            'provider' => 'stripe',
            'provider_payment_id' => (string) Str::uuid(),
            'amount' => $enrollment->price,
            'status' => 'pending',
            'details' => 'Admin seed pending payment',
        ]);

        $enrollment->update([
            'paid' => false,
            'payment_status' => 'pending',
            'payment_provider' => 'stripe',
            'payment_intent_id' => $payment->provider_payment_id,
        ]);

        $this->info("Created pending payment id={$payment->id} for student_id={$student->id} course_id={$course->id} enrollment_id={$enrollment->id}");

        return 0;
    }
}

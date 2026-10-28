<?php

namespace App\Console\Commands;

use App\Models\Enrollment;
use App\Notifications\PaymentReminderNotification;
use Illuminate\Console\Command;

class SendPaymentReminders extends Command
{
    protected $signature = 'enrollments:send-payment-reminders';

    protected $description = 'Send payment reminders to students on day 5 of their trial';

    public function handle(): int
    {
        $enrollments = Enrollment::query()
            ->with(['student', 'course'])
            ->where('paid', false)
            ->whereDate('created_at', now()->subDays(5)->toDateString())
            ->get();

        foreach ($enrollments as $enrollment) {
            $enrollment->student->notify(new PaymentReminderNotification($enrollment));
            $this->info("Reminder sent to {$enrollment->student->email} for {$enrollment->course->title}");
        }

        $this->info("Sent {$enrollments->count()} payment reminder(s).");

        return self::SUCCESS;
    }
}

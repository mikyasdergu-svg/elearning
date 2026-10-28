<?php

namespace App\Notifications;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReminderNotification extends Notification
{
    use Queueable;

    public function __construct(public Enrollment $enrollment) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment reminder for '.$this->enrollment->course->title)
            ->line('Your free trial for '.$this->enrollment->course->title.' is halfway through.')
            ->line('Complete your payment of $'.number_format((float) $this->enrollment->price, 2).' to keep full access after the trial ends.')
            ->action('Complete payment', url('/checkout/'.$this->enrollment->id));
    }
}

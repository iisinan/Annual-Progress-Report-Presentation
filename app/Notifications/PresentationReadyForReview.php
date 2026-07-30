<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Student;

class PresentationReadyForReview extends Notification
{

    public $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Presentation Ready for Review: ' . $this->student->user->name)
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Your assigned student, ' . $this->student->user->name . ' (' . $this->student->matric_number . '), has uploaded their presentation.')
                    ->line('Please log in to your dashboard to review and approve or reject their work.')
                    ->action('Review Presentation', route('dashboard'))
                    ->line('Thank you for your guidance and support!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

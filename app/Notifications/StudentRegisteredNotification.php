<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Student;

class StudentRegisteredNotification extends Notification
{
    use Queueable;

    public $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Preparing for 'mail' later
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Registration Successful - ACETEL Progress Report')
                    ->line('Welcome ' . $this->student->user->name . '!')
                    ->line('Your registration for the ACETEL Progress Report presentation exercise was successful.')
                    ->action('Go to Dashboard', url('/dashboard'))
                    ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Registration Successful',
            'message' => 'Welcome! Your registration (Matric: ' . $this->student->matric_number . ') was successful. Please ensure you upload your presentation before the deadline.',
            'action_url' => '/dashboard',
            'icon' => 'fa-user-check'
        ];
    }
}

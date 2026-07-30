<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Student;

class SupervisorAccountCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public $student;
    public $password;

    public function __construct(Student $student, string $password)
    {
        $this->student = $student;
        $this->password = $password;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Supervisor Account Created - ACETEL')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('An account has been created for you as a Supervisor for ' . $this->student->user->name . ' (' . $this->student->matric_number . ').')
                    ->line('You can use the following credentials to log in and review your student\'s progress:')
                    ->line('**Email:** ' . $notifiable->email)
                    ->line('**Temporary Password:** ' . $this->password)
                    ->action('Login to Dashboard', route('login'))
                    ->line('Please change your password immediately after logging in.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

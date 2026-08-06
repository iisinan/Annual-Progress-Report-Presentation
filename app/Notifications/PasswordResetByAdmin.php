<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetByAdmin extends Notification implements ShouldQueue
{
    use Queueable;

    public $newPassword;

    /**
     * Create a new notification instance.
     */
    public function __construct($newPassword)
    {
        $this->newPassword = $newPassword;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Your Account Password Has Been Reset')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Your account password for the ACETEL Progress Report System has been reset by an administrator.')
                    ->line('Your new temporary password is: **' . $this->newPassword . '**')
                    ->action('Login Now', url('/login'))
                    ->line('We strongly recommend changing your password immediately after logging in.')
                    ->line('If you did not request this change, please contact the administrator immediately.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

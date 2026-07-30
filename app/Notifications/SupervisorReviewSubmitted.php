<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class SupervisorReviewSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public $supervisor;
    public $status;
    public $comments;

    public function __construct(User $supervisor, string $status, ?string $comments)
    {
        $this->supervisor = $supervisor;
        $this->status = $status;
        $this->comments = $comments;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
                    ->subject('Presentation Review Update - ACETEL')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Your supervisor, ' . $this->supervisor->name . ', has reviewed your presentation and marked it as **' . ucfirst($this->status) . '**.');

        if ($this->comments) {
            $message->line('**Comments from Supervisor:**')
                    ->line($this->comments);
        }

        $message->action('View Dashboard', route('dashboard'))
                ->line('If your presentation was rejected, please address the comments and re-upload if necessary.');

        return $message;
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

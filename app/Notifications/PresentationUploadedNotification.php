<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Presentation;

class PresentationUploadedNotification extends Notification
{
    use Queueable;

    public $presentation;

    public function __construct(Presentation $presentation)
    {
        $this->presentation = $presentation;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Presentation Uploaded',
            'message' => 'Your presentation file "' . $this->presentation->original_filename . '" was successfully uploaded.',
            'action_url' => '/dashboard',
            'icon' => 'fa-file-powerpoint'
        ];
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Schedule;

class ScheduleGeneratedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $schedule;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('ACETEL Presentation Schedule Confirmation')
            ->view('emails.schedule_generated', [
                'schedule' => $this->schedule,
                'user' => $notifiable
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Presentation Scheduled',
            'message' => 'Your presentation is scheduled for ' . $this->schedule->presentation_date->format('d M Y') . ' at ' . $this->schedule->start_time->format('h:i A') . '. Venue: ' . $this->schedule->venue,
            'action_url' => '/student/download-slip',
            'icon' => 'fa-calendar-check'
        ];
    }
}

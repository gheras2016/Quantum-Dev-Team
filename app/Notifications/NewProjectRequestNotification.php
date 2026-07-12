<?php

namespace App\Notifications;

use App\Models\ProjectRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewProjectRequestNotification extends Notification
{
    use Queueable;

    public function __construct(public ProjectRequest $projectRequest)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Project Request — '.$this->projectRequest->name)
            ->greeting('New project request received')
            ->line('Name: '.$this->projectRequest->name)
            ->line('Email: '.$this->projectRequest->email)
            ->line('WhatsApp: '.($this->projectRequest->whatsapp ?: '—'))
            ->line('Type: '.$this->projectRequest->project_type)
            ->line('Budget: '.$this->projectRequest->budget_range)
            ->line('Description:')
            ->line($this->projectRequest->description)
            ->action('Open in dashboard', route('admin.project-requests.show', $this->projectRequest));
    }
}

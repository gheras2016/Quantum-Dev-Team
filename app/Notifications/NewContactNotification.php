<?php

namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContactNotification extends Notification
{
    use Queueable;

    public function __construct(public Contact $contact)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Contact Message — '.($this->contact->subject ?: $this->contact->name))
            ->greeting('New contact message received')
            ->line('Name: '.$this->contact->name)
            ->line('Email: '.$this->contact->email)
            ->line('Phone: '.($this->contact->phone ?: '—'))
            ->line('Subject: '.($this->contact->subject ?: '—'))
            ->line('Message:')
            ->line($this->contact->message)
            ->action('Open in dashboard', route('admin.contacts.show', $this->contact));
    }
}

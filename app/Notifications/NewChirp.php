<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Chirp;
use Illuminate\Support\Str;

class NewChirp extends Notification
{
    use Queueable;

    protected $chirp;

    public function __construct(Chirp $chirp)
    {
        $this->chirp = $chirp;
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
            ->subject('New Chirp Created')
            ->line('A new chirp has been created by ' . $this->chirp->user->name)
            ->line('Message: "' . $this->chirp->message . '"')
            ->action('View Chirps', route('chirps.index'));
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

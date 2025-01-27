<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Letter;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LetterRevision extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public User $sender,
        public Letter $letter,
        public string $message,
    ) {}

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
            ->subject("[HRIS Mafindo] Revisi Permohonan Surat \"{$this->letter->title}\"")
            ->markdown('mail.letter-revision', [
                'name' => $this->sender->nama,
                'message' => $this->message,
                'title' => $this->letter->title,
                'id' => $this->letter->id,
            ]);
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

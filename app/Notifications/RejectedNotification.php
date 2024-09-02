<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RejectedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
            ->greeting('Dear ' . $notifiable->name . ',')
            ->line('We regret to inform you that your nursery registration with **Infancia** has been rejected.')
            ->line('Unfortunately, your application did not meet our criteria for acceptance. We encourage you to review the requirements and reapply in the future if possible.')
            ->line('If you have any questions or need further clarification, please do not hesitate to contact us:')
            ->line('**Email:** [info@infancia.com](mailto:info@infancia.com)')
            ->line('**Phone:** +202 22746241')
            ->line('---')
            ->salutation('Best regards,');
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

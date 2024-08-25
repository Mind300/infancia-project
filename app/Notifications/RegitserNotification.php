<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegitserNotification extends Notification
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
            ->line('We are pleased to confirm that we have received your nursery registration with **Infancia**. Our team is currently reviewing your submission, and we will notify you once the review is complete.')
            ->line('In the meantime, if you have any questions or need further assistance, please feel free to contact us:')
            ->line('**Email:** [info@infanica.com](mailto:info@infanica.com)')
            ->line('**Phone:** +202 22746241')
            ->line('---') // Horizontal rule
            ->line('Thank you for trusting **Infancia** with your nursery\'s registration. We look forward to working with you.')
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

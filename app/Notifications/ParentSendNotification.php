<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParentSendNotification extends Notification
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
            ->greeting('Welcome ' . $notifiable->name . ',')
            ->line('Your account with Infancia has been successfully created.')
            ->line('You can now start using our platform to manage and view your child’s activities, schedule, and more.')
            ->line('If you have any questions or need further assistance, please do not hesitate to contact us:')
            ->line('**Email:** [support@infancia.com](mailto:support@infancia.com)')
            ->line('**Phone:** +202 22746241')
            ->line('---')
            ->line('Thank you for choosing **Infancia**. We are excited to support you and your child on this journey.')
            ->salutation('Best regards, Infancia Team');
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

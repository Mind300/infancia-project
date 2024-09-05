<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class paymentSuccessNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $token)
    {
        $this->token = $token;
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
        $url = env('FRONTEND_URL') . 'token=' . $this->token . '&&email=' . $notifiable->email;

        return (new MailMessage)
            ->greeting('Dear ' . $notifiable->name)
            ->line('Payment Success!')
            ->line('Your nursery is now officially part of the Infancia network, and you can start using our platform to manage and promote your services.')
            ->line('If you have any questions or need further assistance, please do not hesitate to contact us:')
            ->line('**Email:** [info@infancia.com](mailto:info@infancia.com)')
            ->line('**Phone:** +202 22746241')
            ->action('Login here', $url)
            ->line('---')
            ->line('Thank you for choosing **Infancia**. We are excited to support your nursery on this journey.')
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

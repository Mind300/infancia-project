<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Http\Controllers\Api\Payments\PaymentController;

class ApproveNotification extends Notification
{
    use Queueable;

    public function __construct(public $nursery, public $token)
    {
        $this->nursery = $nursery;
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = env('FRONTEND_URL') . 'token=' . $this->token . '&&email=' . $notifiable->email;
        $paymentController = new PaymentController($this->nursery);
        $paymentUrl = $paymentController->paymentCreateSubscription();

        return (new MailMessage)
            ->greeting('Dear ' . $notifiable->name . ',')
            ->line('Congratulations! We are pleased to inform you that your nursery registration with **Infancia** has been approved.')
            ->line('Your nursery is now officially part of the Infancia network, and you can start using our platform to manage and promote your services.')
            ->line('If you have any questions or need further assistance, please do not hesitate to contact us:')
            ->line('**Email:** [info@infancia.com](mailto:info@infancia.com)')
            ->line('**Phone:** +202 22746241')
            ->action('Login here', $url)
            ->action('Complete Payment', $paymentUrl)
            ->line('---')
            ->line('Thank you for choosing **Infancia**. We are excited to support your nursery on this journey.')
            ->salutation('Best regards,');
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}

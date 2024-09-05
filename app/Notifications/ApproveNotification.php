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

    public function __construct(public $nursery)
    {
        $this->nursery = $nursery;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $paymentController = new PaymentController($this->nursery);
        $paymentUrl = $paymentController->paymentCreateSubscription();

        return (new MailMessage)
            ->greeting('Dear ' . $notifiable->name . ',')
            ->line('Congratulations! We are pleased to inform you that your nursery registration with **Infancia** has been approved.')
            ->line('Your nursery is now pending for payment, you can using below button to paied the subscribion and after payment succes it get new email about your account.')
            ->line('If you have any questions or need further assistance, please do not hesitate to contact us:')
            ->line('**Email:** [info@infancia.com](mailto:info@infancia.com)')
            ->line('**Phone:** +202 22746241')
            ->action('Complete Payment', $paymentUrl)
            ->line('---')
            ->line('Thank you for choosing **Infancia**')
            ->salutation('Best regards,');
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}

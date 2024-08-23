<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmail extends Notification
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
        // return (new MailMessage)
        //             ->line('Rata Mithiro email confirmation.')
        //             ->line('Click the button below to verify your email address.')
        //             ->action('Verify Email Address', route('verification.verify', $notifiable->verification_token))
        //             ->line('If you did not create an account, no further action is required.')
        //             ->line('Thank you for using our application!');
        return (new MailMessage)
                ->line('Please verify your email address.')
                ->action('Verify Email Address', url(route('verification.verify', [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ], false)))
                ->line('Thank you for using our application!');
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

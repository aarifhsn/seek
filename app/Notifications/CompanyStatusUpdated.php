<?php

namespace App\Notifications;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompanyStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    private $company;
    /**
     * Create a new notification instance.
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->company->status;
        $subject = 'Company Registration ' . ucfirst($status);

        $message = (new MailMessage)
            ->subject($subject)
            ->line('Your company registration has been ' . $status . '.');

        if ($status == 'approved') {
            $message->line('You can now log in to the system and start using all features.')
                ->action('Login Now', url('/login'));
        } else {
            $message->line('Unfortunately, your company registration was not approved at this time.')
                ->line('Reason: ' . ($this->company->rejection_reason ?? 'No specific reason provided.'))
                ->line('You may contact our support team for more information.');
        }

        return $message;
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

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $status = $this->company->status;

        return [
            'title' => 'Company Registration ' . ucfirst($status),
            'message' => 'Your company registration has been ' . $status . '.',
            'status' => $status,
        ];
    }
}

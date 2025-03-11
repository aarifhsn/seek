<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class NewCompanyRegistered extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $company;
    public function __construct($company)
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
        return (new MailMessage)
            ->subject('New Company Registered')
            ->greeting('Hello Admin')
            ->line("A new company **{$this->company->name}** has been registered")
            ->action('Review Company', url('/admin/companies'))
            ->line('Please review the company details and take appropriate action.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => "New company registered: {$this->company->name}",
            'url' => url('/admin/companies'),
        ];
    }

    public function toFilament($notifiable)
    {
        return FilamentNotification::make()
            ->title("New company registered: {$this->company->name}")
            ->body("Please review the company details and take appropriate action.")
            ->action([
                \Filament\Notifications\Actions\Action::make('Review Company')
                    ->url(url('/admin/companies'))
            ])
            ->send();
    }
}

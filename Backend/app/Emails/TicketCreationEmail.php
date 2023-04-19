<?php

namespace App\Emails;

use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketCreationEmail extends Notification implements ShouldBroadcastNow
{

    protected $message;
    protected $url;
    protected $sendAppNotification;
    protected $sendEmail;
    protected $buttonText;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, $url, $sendAppNotification, $sendEmail, $buttonText)
    {
        $this->message = $message;
        $this->url = $url;
        $this->sendAppNotification = $sendAppNotification;
        $this->sendEmail = $sendEmail;
        $this->buttonText = $buttonText;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($this->sendAppNotification && $this->sendEmail) {
            return ['database', 'broadcast', 'mail'];
        } else if ($this->sendAppNotification) {
            return ['database', 'broadcast'];
        } else if ($this->sendEmail) {
            return ['mail'];
        }
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('You have a new notification.')
            ->line($this->message)
            ->action($this->buttonText, url(env('APP_INQUIRY_URL') . $this->url));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'url' => url('#/app/' . $this->url)
        ];
    }

}

<?php
namespace ppeCore\dvtinh\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use ppeCore\dvtinh\Models\User;

class ResetPasswordRequest extends Notification implements ShouldQueue
{
    use Queueable;
    protected $token;
    protected $name;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $name)
    {
        $this->token = $token;
        $this->name = $name;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Dear '. $this->name.'!')
            ->line('The code to reset password for SmileEye is:')
            ->line($this->token)
            ->line('Wish you a wonderfull day!');
    }
}
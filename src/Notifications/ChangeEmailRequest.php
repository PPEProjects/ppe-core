<?php
namespace ppeCore\dvtinh\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use ppeCore\dvtinh\Models\User;

class ChangeEmailRequest extends Notification implements ShouldQueue
{
    use Queueable;
    protected $link;
    protected $name;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($link, $name)
    {
        $this->link = $link;
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
    public function toMail()
    {
        return (new MailMessage)
            ->greeting('Dear '. $this->name.'!')
            ->line("Click to confirm email for SmileEye is:")
            ->line(new HtmlString("<a href='$this->link'>Click here</a>"))
            ->line('Wish you a wonderfull day!');
    }
}
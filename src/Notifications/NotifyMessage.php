<?php

namespace Igorgawrys\Socialler\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NotifyMessage extends Notification  implements ShouldQueue
{ 
    use Queueable;
       
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database','broadcast'];
    }
    
    
/**
 * Get the broadcastable representation of the notification.
 *
 * @param  mixed  $notifiable
 * @return BroadcastMessage
 */
public function toBroadcast($notifiable)
{
    return new BroadcastMessage([
        'content' => "Nowa wiadomość od: ".auth()->user()->full_name,
    ]);
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
                    ->subject('Nowa wiadomość')
                    ->level('success')
                    ->line('Nowa wiadomośc od: '.auth()->user()->full_name)
                    ->action('Kliknij aby zobaczyć','http://socialler.pl/grade/messages/'.auth()->user()->id);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
                 'content' => "Nowa wiadomość od: ".auth()->user()->full_name,
               
        ];
    }
}

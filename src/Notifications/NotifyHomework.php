<?php

namespace Igorgawrys\Socialler\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
class NotifyHomework extends Notification implements ShouldQueue
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
        'content' => "Nowy zadanie domowe w klasie napisane przez:".auth()->user()->full_name,
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
                    ->subject('Nowe zadanie domowe')
                    ->level('success')
                    ->line('Nowy zadanie domowe w klasie')
                    ->action('Kliknij go zobaczyć','http://socialler.pl/grade/homeworks');
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
                 'content' => "Nowy zadanie domowe w klasie napisane przez:".auth()->user()->full_name,
              
        ];
    }
}
<?php

namespace Igorgawrys\Socialler\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;


class QuizJoinedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    public $quiz;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user,$quiz)
    {
        $this->user = $user;
        $this->quiz = $quiz;
    }
            /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\User  $user
     * @param  \App\Order  $order
     * @return array|bool
     */
    public function join()
    {
       return $quiz->grade_id == Auth::user()->grade_id;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('quizes');
    }

}

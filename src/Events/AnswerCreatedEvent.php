<?php

namespace Igorgawrys\Socialler\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
 use App\Entities\Answer;
 use App\Entities\User;
class AnswerCreatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $answer;
    public $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Answer $answer,User $user)
    {
        $this->answer = $answer;
        $this->user = $user;
    }
        /**
     * Authenticate the user's access to the channel.
     *
     * @return array|bool
     */
    public function join()
    {
        return $user->grade_id == Auth::user()->grade_id;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('answers');
    }
}

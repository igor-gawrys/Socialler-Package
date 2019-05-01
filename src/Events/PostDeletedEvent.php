<?php

namespace Igorgawrys\Socialler\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PostDeletedEvent implements ShouldBroadcast
{ 
     use Dispatchable, InteractsWithSockets, SerializesModels;
    public $post;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($post)
    {
        $this->post = $post;
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
        return $post->grade_id == Auth::user()->grade_id;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('posts');
    }
}

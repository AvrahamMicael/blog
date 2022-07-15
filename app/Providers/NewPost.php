<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Subscriber;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class NewPost
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Collection $subscribers;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public Post $post)
    {
        $this->subscribers = Subscriber::all();
    }
}

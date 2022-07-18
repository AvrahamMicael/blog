<?php

namespace App\Providers;

use App\Mail\NewPostMail;
use App\Models\Subscriber;
use App\Providers\NewPost;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailNewPostNotification
{
    /**
     * Handle the event.
     *
     * @param  \App\Providers\NewPost  $event
     * @return void
     */
    public function handle(NewPost $event)
    {
        $event->subscribers->map(fn(Subscriber $sub) => 
            Mail::to($sub)->send(new NewPostMail(post: $event->post, subscriber: $sub))
        );
    }

    public function retryUntil()
    {
        return now()->addMinutes(10);
    }
}

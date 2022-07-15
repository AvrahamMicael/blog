<?php

namespace App\Providers;

use App\Mail\NewPostMail;
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
        Mail::to($event->subscribers)->send(new NewPostMail($event->post));
    }
}

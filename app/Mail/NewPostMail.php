<?php

namespace App\Mail;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewPostMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $post_url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public Post $post)
    {
        $this->post_url = config('app.url_front_end')."/post/$post->slug";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->post->title)
            ->view('emails.newPost');
    }
}

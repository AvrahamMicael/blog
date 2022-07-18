<article>
    <section>
        <h1>{{ $post->title }}</h1>
        @foreach ($post->body as $order => $body_content)
            @if ($order <= 2)
                @if ($body_content['type'] == 'text')
                    <p>
                        {{ $body_content['value'] }}
                    </p>
                @else
                    <img
                        src="{{ $body_content['value'] }}"
                        alt="content_img"
                        style="
                            max-width: 800px;
                            height: auto;
                            display: block;
                            margin-left: auto;
                            margin-right: auto;
                        "
                    >
                @endif
            @endif
        @endforeach
        <a href="{{ $post_url }}">
            {{ count($post->body) > 2 ? 'Read more...' : 'Go to post' }}
        </a>
    </section>
</article>
<br>
<div>
    <small>
        If you'd like to stop receiving these notifications, please click
        <a href="{{ $unsubscribe_link }}">
            this link
        </a>.
    </small>
</div>

@if (session()->has('flash_notification.messages'))
    @foreach (session()->pull('flash_notification.messages') as $message)
        @if ($message['overlay'])
            @include('flash::templates.bulma.modal', [
                'title' => $message['title'],
                'body'  => $message['message']
            ])
        @else
            @if (isset($message['title']) || is_a($message['message'], 'Illuminate\Support\MessageBag'))
                <article class="message is-{{ $message['level'] }}">
                    <div class="message-header">
                        @if (is_a($message['message'], 'Illuminate\Support\MessageBag'))
                            <p><strong>Whoops!</strong> There were some problems with your input.</p>
                        @else
                            <p>{!! $message['title'] !!}</p>
                        @endif
                        <button class="delete" class="bulma-close-notification"></button>
                    </div>
                    <div class="message-body">
                        @if (is_a($message['message'], 'Illuminate\Support\MessageBag'))
                            <ul>
                                @foreach ($message['message']->all('<li>:message</li>') as $error)
                                    {!! $error !!}
                                @endforeach
                            </ul>
                        @else
                            {!! $message['message'] !!}
                        @endif
                    </div>
                </article>
            @else
                <div class="notification is-{{ $message['level'] }}">
                    <button class="delete" class="bulma-close-notification"></button>

                    <p>{!! $message['message'] !!}</p>
                </div>
            @endif
        @endif
    @endforeach
@endif

@if (session()->has('flash_notification.messages'))
    @foreach (session()->pull('flash_notification.messages') as $message)
        @if ($message['overlay'])
            @include('flash::templates.bootstrap.modal', [
                'modalClass' => 'flash-modal',
                'title'      => $message['title'],
                'body'       => $message['message']
            ])
        @else
            <div class="alert alert-{{ $message['level'] }} alert-dismissible">
                @if (is_a($message['message'], 'Illuminate\Support\MessageBag'))
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($message['message']->all('<li>:message</li>') as $error)
                            {!! $error !!}
                        @endforeach
                    </ul>
                @else
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                    @if ( isset($message['title']) )
                        <strong>{!! $message['title'] !!}</strong>
                    @endif

                    <p>{!! $message['message'] !!}</p>
                @endif
            </div>
        @endif
    @endforeach
@endif

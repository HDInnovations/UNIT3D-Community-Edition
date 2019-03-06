@extends('layout.default')

@section('breadcrumb')
    <li class="active">
        <a href="{{ route('message', ['id' => $pm->id]) }}">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                @lang('pm.message')
            </span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="header gradient silver">
            <div class="inner_content">
                <h1>@lang('pm.private') @lang('pm.messages') - @lang('pm.message')</h1>
            </div>
        </div>
        <div class="row">
            @include('partials.pmmenu')
            <div class="col-md-10">
                <div class="block">
                    <h3>Re: {{ $pm->subject }}</h3>
                    @if ($pm->sender_id == auth()->user()->id)
                        <div class="mt-10 message message-unread message-sent">
                            @else
                                <div class="mt-10 message message-read">
                                    @endif
                                    <div class="row message-headers">
                                        <div class="col-sm-4">
                                            <div><strong>@lang('pm.from'):</strong> <a
                                                        href="{{ route('profile', ['username' => $pm->sender->username, 'id' => $pm->sender->id]) }}"
                                                       >{{ $pm->sender->username }}</a>
                                            </div>
                                            <div><strong>@lang('pm.to'):</strong> <a
                                                        href="{{ route('profile', ['username' => $pm->receiver->username, 'id' => $pm->receiver->id]) }}"
                                                       >{{ $pm->receiver->username }}</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
                                            <div><strong>@lang('pm.subject'):</strong> Re: {{ $pm->subject }}
                                            </div>
                                            <div>
                                                <strong>@lang('pm.sent'):</strong> {{ $pm->created_at }}
                                            </div>
                                        </div>
                                        <form role="form" method="POST"
                                              action="{{ route('delete-pm',['id' => $pm->id]) }}">
                                            @csrf
                                            <div class="col-sm-1">
                                                <button type="submit" class="btn btn-sm btn-danger pull-right"
                                                        title="@lang('pm.delete')"><i class="{{ config('other.font-awesome') }} fa-trash"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="row message-body">
                                        <div class="col-sm-12">
                                            @emojione($pm->getMessageHtml())
                                        </div>
                                    </div>
                                </div>
                                <form role="form" method="POST" action="{{ route('reply-pm',['id' => $pm->id]) }}">
                                    @csrf
                                    <div class="form-group">
                                <textarea id="message" name="message" cols="30" rows="10"
                                          class="form-control"></textarea>
                                        <button type="submit" class="btn btn-primary"
                                                style="float:right;">@lang('pm.reply')</button>
                                    </div>
                                </form>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
      $(document).ready(function () {
        $('#message').wysibb({});
        emoji.textcomplete()
      })
    </script>
@endsection

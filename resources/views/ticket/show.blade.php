@extends('layout.default')

@section('title')
    <title>{{ __('ticket.helpdesk') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('tickets.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('ticket.helpdesk') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('tickets.show', ['id' => $ticket->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('ticket.ticket') }} #{{ $ticket->id }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container well">
        <div class="row justify-content-center">
            <div class="col-12">
                @if(session('errors'))
                    <div class="alert alert-danger fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h6><b>{{ __('ticket.fix-errors') }}</b></h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="col-md-8">
                    <div class="panel panel-chat shoutbox">
                        <div class="panel-heading">{{ __('ticket.ticket') }} <i
                                    class="fas fa-hashtag"></i> {{ $ticket->id }}</div>
                        <div class="panel-body">
                        <span class="float-right small text-right">
                            <i class="far fa-user"></i> {{ __('ticket.opened-by') }}
                            <a href="{{ route('users.show', ['username' => $ticket->user->username]) }}">
                                {{ $ticket->user->username }}
                            </a>
                            <i class="far fa-clock"></i>  {{ $ticket->created_at->format('m/d/Y') }}
                            <div class="form-inline">
                                <div class="form-group">
                                    @if(empty($ticket->closed_at))
                                        <form style="display: inline;" role="form" method="POST"
                                              action="{{ route('tickets.close', ['id' => $ticket->id]) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-warning"><i
                                                        class="fas fa-times"></i> {{ __('ticket.close') }}</button>
                                        </form>
                                    @endif
                                    @if(!empty($ticket->closed_at))
                                        <span style="display: inline;"
                                              class="text-danger">{{ __('ticket.closed') }} {{ $ticket->closed_at->format('m/d/Y') }}</span>
                                    @endif
                                        <form style="display: inline;" role="form" method="POST"
                                              action="{{ route('tickets.destroy', ['id' => $ticket->id]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger"><i
                                                        class="fas fa-times"></i> {{ __('ticket.delete') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </span>
                            @if($user->group->is_modo)
                                <div class="btn-group" role="group">
                                    <div class="mb-10 form-inline pull-right">
                                        <div class="form-group">
                                            @if(empty($ticket->staff_id))
                                                <form role="form" method="POST"
                                                      action="{{ route('tickets.assign', ['id' => $ticket->id]) }}">
                                                    @csrf
                                                    <select name="user_id" class="form-control">
                                                        @foreach(App\Models\User::select(['id', 'username'])->whereIn('group_id', App\Models\Group::where('is_modo', 1)->whereNotIn('id', [9])->pluck('id')->toArray())->get() as $user)
                                                            <option value="{{ $user->id }}">{{ $user->username }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit"
                                                            class="btn btn-sm btn-warning">{{ __('ticket.assign') }}</button>
                                                </form>
                                            @else
                                                <form role="form" method="POST"
                                                      action="{{ route('tickets.unassign', ['id' => $ticket->id]) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            class="btn btn-sm btn-warning">{{ __('ticket.unassign') }}</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <hr style="margin: 8px">
                            <div class="row">
                                @if(isset($ticket->staff_id))
                                    <div class="col-md-3">
                                        <label for=""><b>{{ __('ticket.assigned-staff') }}</b></label><br>
                                        <i class="far fa-user"></i>
                                        <a href="{{ route('users.show', ['username' => $ticket->staff->username]) }}">
                                            {{ $ticket->staff->username }}
                                        </a>
                                    </div>
                                @endif
                                <div class="col-md-4">
                                    <label for=""><b>{{ __('ticket.category') }}</b></label><br>
                                    {{ $ticket->category->name }}
                                </div>
                                <div class="col-md-2">
                                    <label for=""><b>{{ __('ticket.priority') }}</b></label><br>
                                    @if($ticket->priority->name === 'Low')
                                        <i class="fas fa-circle text-yellow"></i>
                                    @elseif ($ticket->priority->name === 'Medium')
                                        <i class="fas fa-circle text-orange"></i>
                                    @elseif ($ticket->priority->name === 'High')
                                        <i class="fas fa-circle text-red"></i>
                                    @endif
                                    {{ $ticket->priority->name }}
                                </div>
                                <div class="col-md-3">
                                    <label for=""><b>{{ __('ticket.subject') }}</b></label><br>
                                    {{ $ticket->subject }}
                                </div>
                            </div>
                            <hr style="margin: 8px">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for=""><b>{{ __('ticket.description') }}</b></label><br>
                                    {{ $ticket->body }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="panel panel-chat shoutbox">
                        <div class="panel-heading"><i class="fas fa-file-pdf"></i> {{ __('ticket.attachments') }}</div>
                        <div class="panel-body">
                            @livewire('attachment-upload', ['id' => $ticket->id])
                            @if(count($ticket->attachments))
                                <div class="table-responsive">
                                    <table class="table" style="margin-bottom:0">
                                        <tbody>
                                        @foreach($ticket->attachments as $attachment)
                                            <tr>
                                                <td style="width:100px">
                                                    <form action="{{ route('tickets.attachment.download', $attachment) }}"
                                                          method="POST">
                                                        @csrf
                                                        <button class="btn btn-success btn-sm">{{ __('ticket.download') }}</button>
                                                    </form>
                                                </td>
                                                <td>{{ $attachment->file_name }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                {{ __('ticket.no-attach') }}
                            @endif
                        </div>
                    </div>

                </div>

                <div class="col-md-12 col-sm-12">
                    <div class="panel panel-chat shoutbox">
                        <div class="panel-heading">
                            <h4>
                                <i class="{{ config('other.font-awesome') }} fa-comment"></i> {{ __('common.comments') }}
                            </h4>
                        </div>
                        <div class="panel-body no-padding">
                            <ul class="media-list comments-list">
                                @if (count($ticket->comments) == 0)
                                    <div class="text-center">
                                        <h4 class="text-bold text-danger">
                                            <i class="{{ config('other.font-awesome') }} fa-frown"></i> {{ __('common.no-comments') }}
                                            !
                                        </h4>
                                    </div>
                                @else
                                    @foreach ($ticket->comments as $comment)
                                        <li class="media" style="border-left: 5px solid rgb(1,188,140);">
                                            <div class="media-body">
                                                <a href="{{ route('users.show', ['username' => $comment->user->username]) }}"
                                                   class="pull-left" style="padding-right: 10px;">
                                                    @if ($comment->user->image != null)
                                                        <img src="{{ url('files/img/' . $comment->user->image) }}"
                                                             alt="{{ $comment->user->username }}" class="img-avatar-48"></a>
                                                @else
                                                    <img src="{{ url('img/profile.png') }}"
                                                         alt="{{ $comment->user->username }}"
                                                         class="img-avatar-48"></a>
                                                @endif
                                                <strong>
                                                    <a href="{{ route('users.show', ['username' => $comment->user->username]) }}"
                                                       style="color:{{ $comment->user->group->color }};">
                                                        <span><i class="{{ $comment->user->group->icon }}"></i> {{ $comment->user->username }}</span>
                                                    </a>
                                                </strong>
                                                <span class="text-muted"><small><em>{{ $comment->created_at->toDayDateTimeString() }} ({{ $comment->created_at->diffForHumans() }})</em></small></span>
                                                <div class="pt-5">
                                                    @joypixels($comment->getContentHtml())
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <form role="form" method="POST" action="{{ route('comment_ticket', ['id' => $ticket->id]) }}">
                        @csrf
                        <div class="form-group">
                            <label for="content">{{ __('common.your-comment') }}:</label>
                            <textarea id="content" name="content" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">{{ __('common.submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

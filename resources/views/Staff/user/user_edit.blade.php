@extends('layout.default')

@section('title')
    <title>@lang('common.user') @lang('common.edit') - @lang('staff.staff-dashboard') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="User @lang('common.edit') - @lang('staff.staff-dashboard')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('users.show', ['username' => $user->username]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
        </a>
    </li>
    <li>
        <a href="#" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('common.user') @lang('common.edit')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <h1 class="title">
            <i class="{{ config('other.font-awesome') }} fa-gear"></i>
                @lang('common.edit') @lang('common.user')
            <a href="{{ route('users.show', ['username' => $user->username]) }}">{{ $user->username }}</a>
        </h1>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#account" aria-controls="account" role="tab" data-toggle="tab"
                    aria-expanded="true">@lang('common.account')</a></li>
            <li role="presentation" class=""><a href="#permissions" aria-controls="permissions" role="tab" data-toggle="tab"
                    aria-expanded="false">@lang('user.id-permissions')</a></li>
            <li role="presentation" class=""><a href="#notes" aria-controls="notes" role="tab" data-toggle="tab"
                    aria-expanded="false">@lang('staff.user-notes')</a></li>
            <li role="presentation" class=""><a href="#password" aria-controls="notes" role="tab" data-toggle="tab"
                    aria-expanded="false">@lang('user.change-password')</a></li>
        </ul>
    
        <div class="tab-content block block-titled">
            <div role="tabpanel" class="tab-pane active" id="account">
                <h3>@lang('common.account')</h3>
                <hr>
                <form role="form" method="POST" action="{{ route('user_edit', ['username' => $user->username]) }}">
                    @csrf
                    <div class="form-group">
                        <label for="username">@lang('common.username')</label>
                        <label>
                            <input name="username" type="text" value="{{ $user->username }}" class="form-control">
                        </label>
                    </div>
    
                    <div class="form-group">
                        <label for="email">@lang('common.email')</label>
                        <label>
                            <input name="email" type="email" value="{{ $user->email }}" class="form-control">
                        </label>
                    </div>
    
                    <div class="form-group">
                        <label for="uploaded">@lang('user.total-upload') (Bytes)</label>
                        <label>
                            <input name="uploaded" type="number" value="{{ $user->uploaded }}" class="form-control">
                        </label>
                    </div>
    
                    <div class="form-group">
                        <label for="downloaded">@lang('user.total-download') (Bytes)</label>
                        <label>
                            <input name="downloaded" type="number" value="{{ $user->downloaded }}" class="form-control">
                        </label>
                    </div>
    
                    <div class="form-group">
                        <label for="join-date">@lang('user.member-since')</label>
                        <label>
                            <input name="join-date" type="join-date"
                                value="{{ date('d/m/Y', strtotime($user->created_at)) }}" class="form-control">
                        </label>
                    </div>
    
                    <div class="form-group">
                        <label for="downloaded">@lang('user.title')</label>
                        <label>
                            <input name="title" type="text" value="{{ $user->title }}" class="form-control">
                        </label>
                    </div>
    
                    <div class="form-group">
                        <label for="about">@lang('user.about-me')</label>
                        <label>
                            <textarea name="about" cols="30" rows="10" class="form-control">{{ $user->about }}</textarea>
                        </label>
                    </div>
    
                    <div class="form-group">
                        <label for="about">@lang('common.group')</label>
                        <label>
                            <select name="group_id" class="form-control">
                                <option value="{{ $user->group->id }}">{{ $user->group->name }} (Default)</option>
                                @foreach ($groups as $g)
                                    <option value="{{ $g->id }}">{{ $g->name }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
    
                    <button type="submit" class="btn btn-default">@lang('common.save')</button>
                </form>
            </div>
    
            <div role="tabpanel" class="tab-pane" id="permissions">
                <h3>@lang('user.id-permissions')</h3>
                <hr>
                <form role="form" method="POST" action="{{ route('user_permissions', ['username' => $user->username]) }}">
                    @csrf
                    <label for="hidden" class="control-label">@lang('user.can-download')?</label>
                    <div class="radio-inline">
                    <label><input type="radio" name="can_upload" @if ($user->can_upload == 1) checked @endif
                            value="1">@lang('common.yes')</label>
                    </div>
                    <div class="radio-inline">
                    <label><input type="radio" name="can_upload" @if ($user->can_upload == 0) checked @endif
                            value="0">@lang('common.no')</label>
                    </div>
                    <br>
                    <br>
                    <label for="hidden" class="control-label">@lang('user.can-download')?</label>
                    <div class="radio-inline">
                        <label><input type="radio" name="can_download" @if ($user->can_download == 1) checked
                            @endif value="1">@lang('common.yes')</label>
                    </div>
                    <div class="radio-inline">
                        <label><input type="radio" name="can_download" @if ($user->can_download == 0) checked
                            @endif value="0">@lang('common.no')</label>
                    </div>
                    <br>
                    <br>
                    <label for="hidden" class="control-label">@lang('user.can-comment')?</label>
                    <div class="radio-inline">
                    <label><input type="radio" name="can_comment" @if ($user->can_comment == 1) checked @endif
                            value="1">@lang('common.yes')</label>
                    </div>
                    <div class="radio-inline">
                    <label><input type="radio" name="can_comment" @if ($user->can_comment == 0) checked @endif
                            value="0">@lang('common.no')</label>
                    </div>
                    <br>
                    <br>
                    <label for="hidden" class="control-label">@lang('user.can-invite')?</label>
                    <div class="radio-inline">
                    <label><input type="radio" name="can_invite" @if ($user->can_invite == 1) checked @endif
                            value="1">@lang('common.yes')</label>
                    </div>
                    <div class="radio-inline">
                    <label><input type="radio" name="can_invite" @if ($user->can_invite == 0) checked @endif
                            value="0">@lang('common.no')</label>
                    </div>
                    <br>
                    <br>
                    <label for="hidden" class="control-label">@lang('user.can-request')?</label>
                    <div class="radio-inline">
                    <label><input type="radio" name="can_request" @if ($user->can_request == 1) checked @endif
                            value="1">@lang('common.yes')</label>
                    </div>
                    <div class="radio-inline">
                    <label><input type="radio" name="can_request" @if ($user->can_request == 0) checked @endif
                            value="0">@lang('common.no')</label>
                    </div>
                    <br>
                    <br>
                    <label for="hidden" class="control-label">@lang('user.can-chat')?</label>
                    <div class="radio-inline">
                        <label><input type="radio" name="can_chat" @if ($user->can_chat == 1) checked
                            @endif value="1">@lang('common.yes')</label>
                    </div>
                    <div class="radio-inline">
                        <label><input type="radio" name="can_chat" @if ($user->can_chat == 0) checked
                            @endif value="0">@lang('common.no')</label>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="text-center"><button type="submit" class="btn btn-primary">@lang('common.save')</div>
                    </div>
                </form>
            </div>
    
            <div role="tabpanel" class="tab-pane" id="notes">
                <h3>@lang('common.add') @lang('staff.user-notes')</h3>
                <hr>
                <form role="form" method="POST" action="{{ route('staff.notes.store', ['username' => $user->username]) }}">
                    @csrf
                    <div class="form-group">
                        <label for="message">@lang('staff.user-notes')</label>
                        <label>
                            <textarea name="message" class="form-control"></textarea>
                        </label>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">@lang('common.save')</button>
                </form>
                <hr>
                <h2>@lang('user.note')<span class="text-blue"><strong><i class="{{ config('other.font-awesome') }} fa-note"></i>
                            {{ $notes->count() }} </strong></span></h2>
                <table class="table table-condensed table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>@lang('common.user')</th>
                            <th>@lang('common.staff')</th>
                            <th>@lang('user.note')</th>
                            <th>@lang('user.created-on')</th>
                            <th>@lang('common.delete')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($notes) == 0)
                            <p>The are no notes in database for this user!</p>
                        @else
                            @foreach ($notes as $note)
                                <tr>
                                    <td>
                                        {{ $note->noteduser->username }}
                                    </td>
                                    <td>
                                        {{ $note->staffuser->username }}
                                    </td>
                                    <td>
                                        {{ $note->message }}
                                    </td>
                                    <td>
                                        {{ $note->created_at->toDayDateTimeString() }} ({{ $note->created_at->diffForHumans() }})
                                    </td>
                                    <td>
                                        <form action="{{ route('staff.notes.destroy', ['id' => $note->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger"><i
                                                    class="{{ config('other.font-awesome') }} fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
    
            <div role="tabpanel" class="tab-pane" id="password">
                <h3>@lang('user.change-password')</h3>
                <hr>
                <form role="form" method="POST" action="{{ route('user_password', ['username' => $user->username]) }}">
                    @csrf
                    <div class="form-group">
                        <label for="new_password">@lang('common.new-adj') @lang('passwords.password')</label>
                        <label>
                            <input type="password" name="new_password" class="form-control"
                                   placeholder="@lang('common.new-adj') @lang('common.password')">
                        </label>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">@lang('common.save')</button>
                </form>
            </div>
        </div>
    </div>
@endsection

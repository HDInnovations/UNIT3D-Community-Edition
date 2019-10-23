@extends('layout.default')

@section('title')
    <title>User Edit - Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="User Edit - Staff Dashboard">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('users.show', [ 'username' => $user->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
        </a>
    </li>
    <li>
        <a href="#" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">User Edit</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <h1 class="title"><i class="{{ config('other.font-awesome') }} fa-gear"></i> Edit User <a
                    href="{{ route('users.show', [ 'username' => $user->username]) }}">{{ $user->username }}</a>
        </h1>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#account" aria-controls="account" role="tab"
                                                      data-toggle="tab" aria-expanded="true">Account</a></li>
            <li role="presentation" class=""><a href="#permissions" aria-controls="permissions" role="tab"
                                                data-toggle="tab" aria-expanded="false">Permissions</a></li>
            <li role="presentation" class=""><a href="#notes" aria-controls="notes" role="tab" data-toggle="tab"
                                                aria-expanded="false">Staff Note</a></li>
            <li role="presentation" class=""><a href="#password" aria-controls="notes" role="tab" data-toggle="tab"
                                                aria-expanded="false">Force Update Password</a></li>
        </ul>

        <div class="tab-content block block-titled">
            <div role="tabpanel" class="tab-pane active" id="account">
                <h3>Account</h3>
                <hr>
                <form role="form" method="POST" action="{{ route('user_edit', ['username' => $user->username]) }}">
                @csrf
                <div class="form-group">
                    <label for="username">Username</label>
                    <label>
                        <input name="username" type="text" value="{{ $user->username }}" class="form-control">
                    </label>
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <label>
                        <input name="email" type="email" value="{{ $user->email }}" class="form-control">
                    </label>
                </div>

                <div class="form-group">
                    <label for="uploaded">Uploaded (In Bytes!)</label>
                    <label>
                        <input name="uploaded" type="number" value="{{ $user->uploaded }}" class="form-control">
                    </label>
                </div>

                <div class="form-group">
                    <label for="downloaded">Downloaded (In Bytes!)</label>
                    <label>
                        <input name="downloaded" type="number" value="{{ $user->downloaded }}" class="form-control">
                    </label>
                </div>

                <div class="form-group">
                    <label for="join-date">Join date</label>
                    <label>
                        <input name="join-date" type="join-date" value="{{ date('d/m/Y', strtotime($user->created_at)) }}"
                               class="form-control">
                    </label>
                </div>

                    <div class="form-group">
                        <label for="downloaded">Title</label>
                        <label>
                            <input name="title" type="text" value="{{ $user->title }}" class="form-control">
                        </label>
                    </div>

                <div class="form-group">
                    <label for="about">About</label>
                    <label>
                        <textarea name="about" cols="30" rows="10" class="form-control">{{ $user->about }}</textarea>
                    </label>
                </div>

                <div class="form-group">
                    <label>
                        <select name="group_id" class="form-control">
                            <option value="{{ $user->group->id }}">{{ $user->group->name }} (Default)</option>
                            @foreach ($groups as $g)
                                <option value="{{ $g->id }}">{{ $g->name }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>

                <button type="submit" class="btn btn-default">Save</button>
                </form>
            </div>

            <div role="tabpanel" class="tab-pane" id="permissions">
                <h3>Permissions</h3>
                <hr>
                <form role="form" method="POST" action="{{ route('user_permissions', ['username' => $user->username]) }}">
                @csrf
                <label for="hidden" class="control-label">Can Upload?</label>
                <div class="radio-inline">
                    <label><input type="radio" name="can_upload" @if ($user->can_upload == 1) checked @endif value="1">@lang('common.yes')</label>
                </div>
                <div class="radio-inline">
                    <label><input type="radio" name="can_upload" @if ($user->can_upload == 0) checked @endif value="0">@lang('common.no')</label>
                </div>
                <br>
                <br>
                <label for="hidden" class="control-label">Can Download?</label>
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
                <label for="hidden" class="control-label">Can Comment?</label>
                <div class="radio-inline">
                    <label><input type="radio" name="can_comment" @if ($user->can_comment == 1) checked @endif value="1">@lang('common.yes')</label>
                </div>
                <div class="radio-inline">
                    <label><input type="radio" name="can_comment" @if ($user->can_comment == 0) checked @endif value="0">@lang('common.no')</label>
                </div>
                <br>
                <br>
                <label for="hidden" class="control-label">Can Invite?</label>
                <div class="radio-inline">
                    <label><input type="radio" name="can_invite" @if ($user->can_invite == 1) checked @endif value="1">@lang('common.yes')</label>
                </div>
                <div class="radio-inline">
                    <label><input type="radio" name="can_invite" @if ($user->can_invite == 0) checked @endif value="0">@lang('common.no')</label>
                </div>
                <br>
                <br>
                <label for="hidden" class="control-label">Can Request?</label>
                <div class="radio-inline">
                    <label><input type="radio" name="can_request" @if ($user->can_request == 1) checked @endif value="1">@lang('common.yes')</label>
                </div>
                <div class="radio-inline">
                    <label><input type="radio" name="can_request" @if ($user->can_request == 0) checked @endif value="0">@lang('common.no')</label>
                </div>
                <br>
                <br>
                <label for="hidden" class="control-label">Can Chat?</label>
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
                    <div class="text-center"><input class="btn btn-primary" type="submit" value="Save"></div>
                </div>
                </form>
            </div>

            <div role="tabpanel" class="tab-pane" id="notes">
                <h3>Add Staff Note</h3>
                <hr>
                <form role="form" method="POST" action="{{ route('staff.notes.store', ['username' => $user->username]) }}">
                    @csrf
                <div class="form-group">
                    <label for="message">Note</label>
                    <label>
                        <textarea name="message" class="form-control"></textarea>
                    </label>
                </div>
                <br>
                <button type="submit" class="btn btn-primary">Save</button>
                </form>
                <hr>
                <h2>Notes <span class="text-blue"><strong><i
                                    class="{{ config('other.font-awesome') }} fa-note"></i> {{ $notes->count() }} </strong></span></h2>
                <table class="table table-condensed table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>User</th>
                        <th>Staff</th>
                        <th>Message</th>
                        <th>Created On</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($notes) == 0)
                        <p>The are no notes in database for this user!</p>
                    @else
                        @foreach ($notes as $n)
                            <tr>
                                <td>
                                    {{ $n->noteduser->username }}
                                </td>
                                <td>
                                    {{ $n->staffuser->username }}
                                </td>
                                <td>
                                    {{ $n->message }}
                                </td>
                                <td>
                                    {{ $n->created_at->toDayDateTimeString() }} ({{ $n->created_at->diffForHumans() }})
                                </td>
                                <td>
                                    <a href="{{ route('staff.notes.destroy', ['id' => $n->id]) }}"
                                       class="btn btn-xs btn-danger">
                                        <i class="{{ config('other.font-awesome') }} fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>

            <div role="tabpanel" class="tab-pane" id="password">
                <h3>Force Update Password</h3>
                <hr>
                <form role="form" method="POST" action="{{ route('user_password', ['username' => $user->username]) }}">
                    @csrf
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <label>
                        <input type="password" name="new_password" class="form-control" placeholder="New Password">
                    </label>
                </div>
                <br>
                <button type="submit" class="btn btn-primary">Make The Switch!</button>
                </form>
            </div>
        </div>
    </div>
@endsection

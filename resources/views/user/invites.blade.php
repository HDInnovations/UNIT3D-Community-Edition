@extends('layout.default')

@section('title')
    <title>{{ $owner->username }} - {{ __('user.invites') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('users.show', ['username' => $owner->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $owner->username }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('invites.index', ['username' => $owner->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title"
                  class="l-breadcrumb-item-link-title">{{ $owner->username }} {{ __('user.invites') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="block">
            @php $user = $owner @endphp
            @include('user.buttons.invite')
            <div class="some-padding">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-condensed table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>{{ __('user.sender') }}</th>
                                    <th>{{ __('common.email') }}</th>
                                    <th>{{ __('user.code') }}</th>
                                    <th>{{ __('user.created-on') }}</th>
                                    <th>{{ __('user.expires-on') }}</th>
                                    <th>{{ __('user.accepted-by') }}</th>
                                    <th>{{ __('user.accepted-at') }}</th>
                                    <th>{{ __('common.resend') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($invites) == 0)
                                    <p>{{ __('user.no-logs') }}</p>
                                @else
                                    @foreach ($invites as $invite)
                                        <tr>
                                            <td>
                                                <a href="{{ route('users.show', ['username' => $invite->sender->username]) }}">
                                                        <span class="text-bold"
                                                              style="color:{{ $invite->sender->group->color }}; ">
                                                            <i class="{{ $invite->sender->group->icon }}"></i>
                                                            {{ $invite->sender->username }}
                                                        </span>
                                                </a>
                                            </td>
                                            <td>
                                                {{ $invite->email }}
                                            </td>
                                            <td>
                                                {{ $invite->code }}
                                            </td>
                                            <td>
                                                {{ $invite->created_at }}
                                            </td>
                                            <td>
                                                {{ $invite->expires_on }}
                                            </td>
                                            <td>
                                                @if ($invite->accepted_by != null && $invite->accepted_by != 1)
                                                    <a
                                                            href="{{ route('users.show', ['username' => $invite->receiver->username]) }}">
                                                            <span class="text-bold"
                                                                  style="color:{{ $invite->receiver->group->color }}; ">
                                                                <i class="{{ $invite->receiver->group->icon }}"></i>
                                                                {{ $invite->receiver->username }}
                                                            </span>
                                                    </a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if ($invite->accepted_at != null)
                                                    {{ $invite->accepted_at }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('invites.send', ['id' => $invite->id]) }}"
                                                      method="POST">
                                                    @csrf
                                                    <button type="submit" @if ($invite->accepted_at !== null) class="btn btn-xs
                                                            btn-danger disabled" @endif class="btn btn-xs btn-success">
                                                        <i class="{{ config('other.font-awesome') }} fa-sync-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            <div class="text-center">
                                {{ $invites->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

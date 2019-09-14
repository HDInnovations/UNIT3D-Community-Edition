@extends('layout.default')

@section('title')
    <title>@lang('common.members') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('user.members-desc', ['title' => config('other.title')])">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('members') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('common.members')</span>
        </a>
    </li>
@endsection


@section('content')
    <div class="box container">
        <form action="{{route('userSearch')}}" method="GET" class="form-inline pull-right">
            <input type="text" name="username" id="username" size="25" placeholder="@lang('user.search')"
                   class="form-control">
            <button type="submit" class="btn btn-success">
                <i class="{{ config('other.font-awesome') }} fa-search"></i> Search
            </button>
        </form>
        <div class="profil">
            <div class="header gradient silver">
                <div class="inner_content">
                    <div class="page-title"><h1>@lang('common.members')</h1></div>
                </div>
            </div>
            <table class="table table-condensed table-striped table-bordered">
                <thead>
                <tr>
                    <th>@lang('user.image')</th>
                    <th>@lang('common.username')</th>
                    <th>@lang('common.group')</th>
                    <th>@lang('user.registration-date')</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>
                            @if ($user->image != null)
                                <img src="{{ url('files/img/' . $user->image) }}" alt="{{ $user->username }}"
                                     class="members-table-img img-thumbnail">
                            @else
                                <img src="{{ url('img/profile.png') }}" alt="{{ $user->username }}"
                                     class="members-table-img img-thumbnail">
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('profile', ['username' => $user->username, 'id' => $user->id]) }}">{{ $user->username }}</a>
                        </td>
                        <td>{{ $user->group->name }}</td>
                        <td>{{ date('d M Y', strtotime($user->created_at)) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-center">
            {{ $users->links() }}
        </div>
    </div>
@endsection

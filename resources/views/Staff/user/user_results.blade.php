@extends('layout.default')

@section('title')
    <title>@lang('common.user') Search Results - @lang('staff.staff-dashboard') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="User Search Results - @lang('staff.staff-dashboard')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user_search') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('common.user') @lang('common.search')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user_results') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                @lang('common.user') @lang('common.search') @lang('common.results')
            </span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="block">
                    <form action="{{ route('user_results') }}" method="GET" class="form-inline pull-right">
                        <label for="username"></label><input type="text" name="username" id="username" size="25"
                            placeholder="@lang('user.search')" class="form-control">
                        <button type="submit" class="btn btn-success">
                            <i class="{{ config('other.font-awesome') }} fa-search"></i> Search
                        </button>
                    </form>
                    <table class="table table-hover members-table middle-align">
                        <thead>
                            <tr>
                                <th class="hidden-xs hidden-sm"></th>
                                <th>@lang('common.name') /  @lang('common.group')</th>
                                <th class="hidden-xs hidden-sm">E-Mail</th>
                                <th>ID</th>
                                <th>@lang('user.settings')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="user-image hidden-xs hidden-sm">
                                        @if ($user->image != null)
                                            <img src="{{ url('files/img/' . $user->image) }}" alt="{{ $user->username }}"
                                            class="img-circle"> @else
                                            <img src="{{ url('img/profile.png') }}" alt="{{ $user->username }}" class="img-circle">
                                        @endif
                                    </td>
                                    <td class="user-name"><a href="{{ route('users.show', ['username' => $user->username]) }}"
                                            class="name">{{ $user->username }}</a> <span>{{ $user->group->name }}</span>
                                    </td>
                                    @if (auth()->user()->group->is_modo)
                                        <td class="hidden-xs hidden-sm"><span class="email">{{ $user->email }}</span></td>
                                        <td class="user-id">
                                            {{ $user->id }}
                                        </td>
                                        <td class="action-links">
                                            <a href="{{ route('user_setting', ['username' => $user->username]) }}" class="edit">
                                                <i class="{{ config('other.font-awesome') }} fa-pencil"></i>
                                                @lang('common.edit') @lang('user.profile')
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <ul>
                            {{ $users->links() }}
                        </ul>
                    </div>
                </div>
                </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
@endsection

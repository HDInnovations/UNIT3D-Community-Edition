@extends('layout.default')

@section('title')
    <title xmlns="http://www.w3.org/1999/html">@lang('stat.stats') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li class="active">
        <a href="{{ route('stats') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('stat.stats')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('groups') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('stat.groups')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        @include('partials.statsgroupmenu')
        <div class="block">
            <h2>@lang('stat.groups')</h2>
            <hr>
            <p class="text-red"><strong><i class="{{ config('other.font-awesome') }} fa-users"></i>
                    @lang('stat.groups')</strong>
                (@lang('stat.users-per-group'))</p>
            <div class="row">
                @php $cols = 0;  @endphp
                @foreach ($groups as $group)
                    <div class="col-sm-12 col-md-6 col-lg-4" style="margin: auto">
                        <div class="well" style="margin: 10px;">
                            <div class="text-center">
                                <a href="{{ route('group', ['id' => $group->id]) }}">
                                    <h2 style="color:{{ $group->color }};">
                                        <i class="{{ $group->icon }}" aria-hidden="true"></i>&nbsp;{{ $group->name }}</h2>
                                </a>
                                <p class="text-blue text-bold">Primary Users: {{ \App\Models\User::where('role_id','=', $group->id)->count()  }}</p>
                                <p class="text-black">Total Users: {{ $group
                                            ->users()
                                            ->withTrashed()
                                            ->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @php $cols = $cols + 1; @endphp
                    @if ($cols == 3)
                        </div>
                        <div class="row">
                        @php $cols = 0; @endphp
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection

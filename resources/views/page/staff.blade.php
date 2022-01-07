@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ config('other.title') }}
                {{ __('common.staff') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="col-md-12 page">
            @foreach ($staff as $s)
            <div class="row oper-list">
                <h3><u>{{ $s->name }}</u></h3>
                @foreach($s->users as $user)
                    <div class="col-xs-6 col-sm-4 col-md-3">
                        <div class="text-center oper-item" style="background-color: {{ $s->color }};">
                            <a href="{{ route('users.show', ['username' => $user->username]) }}" style="color:#ffffff;">
                                <h1>{{ $user->username }}</h1>
                            </a>
                            <span class="badge-user">{{ __('page.staff-group') }}: {{ $s->name }}</span>
                            <br>
                            <span class="badge-user">{{ __('page.staff-title') }}: {{ $user->title }}</span>
                            <i class="fal {{ $s->icon }} oper-icon"></i>
                        </div>
                    </div>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>
@endsection

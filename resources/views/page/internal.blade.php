@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('internal') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ config('other.title') }}
                {{ __('common.internal') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="col-md-12 page">

            <!-- Internals in Groups -->
            @foreach ($internals as $internal)
                <div class="row oper-list">
                    <div class="page-title" style="text-align:center;">
                        <h1>
                            @if ($internal->icon !== 'none')
                                <i class="{{ $internal->icon }}"></i>
                            @else
                                <i class="fas fa-magic"></i>
                            @endif
                            {{ $internal->name }}
                        </h1>
                    </div>
                    @foreach ($internal->users as $user)
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <div class="text-center oper-item" style="background-color: {{ $user->group->color }}; background-image: {{ $internal->effect }};">
                                    <a href="{{ route('users.show', ['username' => $user->username]) }}" style="color:#ffffff;">
                                        <h1>{{ $user->username }}</h1>
                                    </a>
                                    <span class="badge-user" style="margin-bottom:5px;">{{ __('page.staff-group') }}: {{ $internal->name }}</span>
                                    <br>
                                    @if ($user->title != null)
                                        <span class="badge-user">{{ __('page.staff-title') }}: {{ $user->title }}</span>
                                    @else
                                        <span class="badge-user" style="visibility:hidden;"></span>
                                    @endif
                                    <i class="fal {{ $user->group->icon }} oper-icon"></i>
                                </div>
                            </div>
                    @endforeach
                </div>
                <br>
                <hr>
            @endforeach
        </div>
    </div>
@endsection

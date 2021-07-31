@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('internal') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ config('other.title') }}
                @lang('common.internal')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="col-md-12 page">
            <div class="header gradient silver">
                <div class="inner_content">
                    <div class="page-title">
                        <h1>{{ config('other.title') }} @lang('common.internal')</h1>
                    </div>
                </div>
            </div>

            <!-- Internals in Groups -->
            @foreach ($internal_group as $ig)
                <div class="row oper-list">
                    <div class="page-title" style="text-align:center;">
                        <h1><u>
                            @if ($ig->icon != "none")
                                <i class="{{ config('other.font-awesome') }} {{ $ig->icon }}"></i>
                            @else
                                <i class="fas fa-magic"></i>
                            @endif
                            {{ $ig->name }}
                        </u></h1>
                    </div>
                    @foreach ($internal_user as $iu)
                        @if ($iu->internal_id == $ig->id)
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <div class="text-center oper-item" style="background-color: {{ $iu->color }};">
                                    <a href="{{ route('users.show', ['username' => $iu->username]) }}" style="color:#ffffff;">
                                        <h1>{{ $iu->username }}</h1>
                                    </a>
                                    <span class="badge-user" style="margin-bottom:5px;">@lang('page.staff-group'): {{ $iu->name }}</span>
                                    <br>
                                    @if ($iu->title != null)
                                        <span class="badge-user">@lang('page.staff-title'): {{ $iu->title }}</span>
                                    @else
                                        <span class="badge-user" style="visibility:hidden;"></span>
                                    @endif
                                    <i class="{{ $iu->icon }} oper-icon"></i>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <br>
                <hr>
            @endforeach

            <!-- Internals not in Groups -->
            @foreach ($internal_user as $iu)
                @if ($iu->internal_id == "0")
                    <div class="row oper-list">
                        <div class="page-title" style="text-align:center;">
                            <h1><u>
                                @if ($ig->icon != "none")
                                    <i class="{{ config('other.font-awesome') }} fa-magic"></i>
                                @else
                                    <i class="fas fa-magic"></i>
                                @endif
                                {{ $iu->username }}
                            </u></h1>
                        </div>
                        <div class="col-xs-6 col-sm-4 col-md-3">
                            <div class="text-center oper-item" style="background-color: {{ $iu->color }};">
                                <a href="{{ route('users.show', ['username' => $iu->username]) }}" style="color:#ffffff;">
                                    <h1>{{ $iu->username }}</h1>
                                </a>
                                <span class="badge-user" style="margin-bottom:5px;">@lang('page.staff-group'): {{ $iu->name }}</span>
                                <br>
                                @if ($iu->title != null)
                                    <span class="badge-user">@lang('page.staff-title'): {{ $iu->title }}</span>
                                @else
                                    <span class="badge-user" style="visibility:hidden;"></span>
                                @endif
                                <i class="{{ $iu->icon }} oper-icon"></i>
                            </div>
                        </div>
                    </div>
                    <br>
                    <hr>
                @endif
            @endforeach

        </div>
    </div>
@endsection

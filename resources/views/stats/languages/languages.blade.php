@extends('layout.default')

@section('title')
    <title>@lang('stat.stats') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li class="active">
        <a href="{{ route('stats') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('stat.stats')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('languages') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('stat.languages')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <h2>@lang('stat.languages')</h2>
            <hr>
            <p class="text-red"><strong><i class="{{ config('other.font-awesome') }} fa-flag"></i>
                    @lang('stat.languages')</strong></p>
            <div class="row col-md-offset-1">
                @foreach ($languages as $code => $name)
                    <div class="well col-md-2" style="margin: 10px;">
                        <div class="text-center">
                            <img src="{{ url('img/flags/' . $code . '.png') }}" alt="{{ $name }}" style=" border-radius: 5px"
                                height="100px" width="150px" />
                            <br>
                            <br>
                            <p class="text-success">{{ $name }}</p>
                    @php $count = App\Models\User::where('locale', '=', $code)->count(); @endphp
                            <span class="badge-extra text-blue">Used By {{ $count }} Users</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

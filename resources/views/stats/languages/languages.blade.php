@extends('layout.default')

@section('title')
    <title>{{ __('stat.stats') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li class="active">
        <a href="{{ route('stats') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('stat.stats') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('languages') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('stat.languages') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <h2>{{ __('stat.languages') }}</h2>
            <hr>
            <div class="row col-md-offset-1">
                @foreach ($languages as $code => $name)
                    <div class="well col-md-3" style="margin: 10px;">
                        <div class="text-center">
                            <h3 class="text-success">{{ $name }}</h3>
                            @php $count = App\Models\User::where('locale', '=', $code)->count() @endphp
                            <span class="badge-extra text-blue">Used By {{ $count }} Users</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

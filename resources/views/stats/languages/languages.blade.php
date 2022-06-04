@extends('layout.default')

@section('title')
    <title>{{ __('stat.stats') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('stats') }}" class="breadcrumb__link">
            {{ __('stat.stats') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('stat.languages') }}
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

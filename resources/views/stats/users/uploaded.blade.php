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
        <a href="{{ route('uploaded') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('stat.top-uploaders') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        @include('partials.statsusermenu')

        <div class="block">
            <h2>{{ __('stat.top-uploaders') }} ({{ strtolower(__('stat.by-volume')) }})</h2>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <p class="text-green"><strong><i
                                    class="{{ config('other.font-awesome') }} fa-arrow-up"></i> {{ __('stat.top-uploaders') }}
                        </strong> ({{ strtolower(__('stat.by-volume')) }})</p>
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('common.user') }}</th>
                            <th>{{ __('common.upload') }}</th>
                            <th>{{ __('common.download') }}</th>
                            <th>{{ __('common.ratio') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($uploaded as $key => $u)
                            <tr>
                                <td>
                                    {{ ++$key }}
                                </td>
                                <td @if (auth()->user()->username == $u->username) class="mentions" @endif>
                                    @if ($u->private_profile == 1)
                                        <span class="badge-user text-bold"><span class="text-orange"><i
                                                        class="{{ config('other.font-awesome') }} fa-eye-slash"
                                                        aria-hidden="true"></i>{{ strtoupper(__('common.hidden')) }}</span>@if (auth()->user()->id == $u->id || auth()->user()->group->is_modo)
                                                <a href="{{ route('users.show', ['username' => $u->username]) }}">({{ $u->username }}
                                                    )</a></span>
                                    @endif
                                    @else
                                        <span class="badge-user text-bold"><a
                                                    href="{{ route('users.show', ['username' => $u->username]) }}">{{ $u->username }}</a></span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-green">{{ \App\Helpers\StringHelper::formatBytes($u->uploaded, 2) }}</span>
                                </td>
                                <td>{{ \App\Helpers\StringHelper::formatBytes($u->downloaded, 2) }}</td>
                                <td>
                                    <span>{{ $u->getRatio() }}</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layout.default')

@section('title')
    <title>{{ trans('user.active-table') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('myactive', ['username' => $user->username, 'id' => $user->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('user.active-table') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <h1 class="title">{{ trans('user.active-table') }}</h1>
        <div class="block">
            <!-- Active -->
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <div class="head"><strong>{{ trans('user.active-torrents') }}</strong></div>
                    <thead>
                    <th>@sortablelink('name', trans('torrent.name'))</th>
                    <th>{{ trans('torrent.category') }}</th>
                    <th>@sortablelink('size', trans('torrent.size'))</th>
                    <th>@sortablelink('uploaded', trans('torrent.uploaded'))</th>
                    <th>@sortablelink('downloaded', trans('torrent.downloaded'))</th>
                    <th>@sortablelink('left', trans('torrent.left'))</th>
                    <th>@sortablelink('agent', trans('torrent.agent'))</th>
                    <th>@sortablelink('seeder', trans('torrent.seeder'))</th>
                    </thead>
                    <tbody>
                    @foreach ($active as $p)
                        <tr>
                            <td>
                                <a class="view-torrent" data-id="{{ $p->torrent_id }}"
                                   data-slug="{{ $p->torrent->slug }}"
                                   href="{{ route('torrent', ['slug' => $p->torrent->slug, 'id' => $p->torrent_id]) }}"
                                   data-toggle="tooltip" title="{{ $p->torrent->name }}"
                                   data-original-title="{{ trans('user.moderated-by', ['mod' => App\User::find($p->torrent->moderated_by)->username]) }} {{ $p->torrent->moderated_at->diffForHumans() }}">{{ $p->torrent->name }}</a>
                            </td>
                            <td>
                                <a href="{{ route('category', ['slug' => $p->torrent->category->slug, 'id' => $p->torrent->category->id]) }}">{{ $p->torrent->category->name }}</a>
                            </td>
                            <td>
                                <span class="badge-extra text-blue text-bold"> {{ $p->torrent->getSize() }}</span>
                            </td>
                            <td>
                                <span class="badge-extra text-green text-bold"> {{ App\Helpers\StringHelper::formatBytes($p->uploaded , 2) }}</span>
                            </td>
                            <td>
                                <span class="badge-extra text-red text-bold"> {{ App\Helpers\StringHelper::formatBytes($p->downloaded , 2) }}</span>
                            </td>
                            <td>
                                <span class="badge-extra text-orange text-bold">{{ \App\Helpers\StringHelper::formatBytes($p->left, 2) }}</span>
                            </td>
                            <td>
                                <span class="badge-extra text-purple text-bold">{{ $p->agent ? $p->agent : trans('common.unknown') }}</span>
                            </td>
                            @if ($p->seeder == 0)
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped active" role="progressbar"
                                             aria-valuenow="{{ $p->downloaded / $p->torrent->size * 100 }}"
                                             aria-valuemin="0" aria-valuemax="100"
                                             style="width: {{ $p->downloaded / $p->torrent->size * 100 }}%;">
                                            {{ round($p->downloaded / $p->torrent->size * 100) }}%
                                        </div>
                                    </div>
                                </td>
                            @elseif ($p->seeder == 1)
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped active" role="progressbar"
                                             aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"
                                             style="width: 100%;">
                                            100%
                                        </div>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! $active->appends(\Request::except('page'))->render() !!}
            </div>
        </div>
@endsection

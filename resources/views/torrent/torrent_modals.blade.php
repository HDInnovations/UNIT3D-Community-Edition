<div class="modal fade" id="modal_torrent_report" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog{{ modal_style() }}">
        <div class="modal-content">
            <meta charset="utf-8">
            <title>{{ __('common.report') }} {{ strtolower(__('torrent.torrent')) }}: {{ $torrent->name }}</title>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('common.close') }}"><span
                            aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ __('common.report') }}
                    {{ strtolower(__('torrent.torrent')) }}
                    : {{ $torrent->name }}</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" method="POST"
                      action="{{ route('report_torrent', ['id' => $torrent->id]) }}">
                    <div class="form-group">
                        @csrf
                        <label for="file_name" class="col-sm-2 control-label">{{ __('torrent.torrent') }}</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="torrent_id" value="{{ $torrent->id }}">
                            <p class="form-control-static">{{ $torrent->name }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="report_reason" class="col-sm-2 control-label">{{ __('common.reason') }}</label>
                        <div class="col-sm-10">
                            <label for="message"></label>
                            <textarea class="form-control" rows="5" name="message" cols="50" id="message"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input class="btn btn-danger" type="submit" value="{{ __('common.report') }}">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" type="button" data-dismiss="modal">{{ __('common.close') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_torrent_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog{{ modal_style() }}">
        <div class="modal-content">
            <meta charset="utf-8">
            <title>{{ __('common.delete') }} {{ strtolower(__('torrent.torrent')) }}: {{ $torrent->name }}</title>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('common.close') }}"><span
                            aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ __('common.delete') }}
                    {{ strtolower(__('torrent.torrent')) }}
                    : {{ $torrent->name }}</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <form method="POST" action="{{ route('delete') }}">
                        @csrf
                        <input id="type" name="type" type="hidden" value="Torrent">
                        <input id="id" name="id" type="hidden" value="{{ $torrent->id }}">
                        <input id="slug" name="slug" type="hidden" value="{{ $torrent->slug }}">
                        <label for="file_name" class="col-sm-2 control-label">{{ __('torrent.torrent') }}</label>
                        <div class="col-sm-10">
                            <input id="title" name="title" type="hidden" value="{{ $torrent->name }}">
                            <p class="form-control-static">{{ $torrent->name }}</p>
                        </div>
                        <label for="report_reason" class="col-sm-2 control-label">{{ __('common.reason') }}</label>
                        <div class="col-sm-10">
                            <label for="message"></label>
                            <textarea class="form-control" rows="5" name="message" cols="50" id="message"></textarea>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <input class="btn btn-danger" type="submit" value="{{ __('common.delete') }}">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" type="button" data-dismiss="modal">{{ __('common.close') }}</button>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg{{ modal_style() }}">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">
                    {{ __('common.files') }}
                    <span
                            class="pull-right"
                            style="display: inline-block; margin-right: 24px"
                    >
                        ({{ $torrent->files->count() }})
                        {{ App\Helpers\StringHelper::formatBytes($torrent->size, 2) }}
                        </span>
                </h4>
            </div>
            <div class="modal-body" x-data="{tab: 1}">
                <ul class="nav nav-tabs mb-12">
                    <li class="col-md-6" :class="{'active': tab === 1}">
                        <a @click.prevent="tab = 1" style="cursor: pointer">
                            <i class="{{ config('other.font-awesome') }} fa-sitemap"></i> Hierarchy
                        </a>
                    </li>
                    <li class="col-md-6" :class="{'active': tab === 2}">
                        <a @click.prevent="tab = 2" style="cursor: pointer">
                            <i class="{{ config('other.font-awesome') }} fa-list"></i> List
                        </a>
                    </li>
                </ul>
                <div>
                    <div x-show="tab === 1">
                        @foreach ($files = $torrent->files->sortBy('name')->values()->sortBy(fn ($f) => dirname($f->name)."/~~~", SORT_NATURAL)->values() as $file)
                        @php $prevNodes = explode("/", $files[$loop->index - 1]->name ?? " ") @endphp
                        @foreach ($nodes = explode("/", $file->name) as $node)
                        @if (($prevNodes[$loop->index] ?? "") != $node)
                        @for ($depth = count($prevNodes); $depth > $loop->index; $depth--)
                        </details>
                        @endfor

                        @for ($depth = $loop->index; $depth < $loop->count; $depth++)
                            <details style="@if ($depth != 0) margin-left: 20px; @endif">
                                <summary style="padding: 8px; @if ($depth != $loop->count - 1) cursor: pointer; @endif">
                                                <span style="display: grid; grid-template-areas: 'icon1 icon2 folder count . size';
                                                    grid-template-columns: 24px 24px auto auto 1fr auto;">

                                                    @if ($depth == $loop->count - 1)
                                                        <i style="grid-area: icon1"></i>
                                                        <i class="{{ config('other.font-awesome') }} fa-file"
                                                           style="grid-area: icon2; padding-right: 4px"></i>
                                                        <span style="padding-right: 4px">
                                                            {{ $nodes[$depth] }}
                                                        </span>
                                                        <span
                                                                style="grid-area: size; white-space: nowrap; text-align: right;"
                                                        >
                                                            {{ $file->getSize() }}
                                                        </span>
                                                    @else
                                                        <i class="{{ config('other.font-awesome') }} fa-caret-right"
                                                           style="grid-area: icon1;"></i>
                                                        <i class="{{ config('other.font-awesome') }} fa-folder"
                                                           style="grid-area: icon2; padding-right: 4px;"></i>
                                                        <span style="padding-right: 4px">
                                                            {{ $nodes[$depth] }}
                                                        </span>

                                                        @php
                                                            $filteredFiles = $files->filter(fn ($value) =>
                                                                str_starts_with(
                                                                    $value->name,
                                                                    implode("/", array_slice($nodes, 0, $depth + 1))."/"
                                                                )
                                                            )
                                                        @endphp

                                                        <span class="text-info"
                                                              style="grid-area: count; padding-right: 4px;">
                                                            ({{ $filteredFiles->count() }})
                                                        </span>
                                                        <span
                                                                class="text-info"
                                                                style="grid-area: size; white-space: nowrap; text-align: right;"
                                                        >
                                                            {{ App\Helpers\StringHelper::formatBytes($filteredFiles->sum('size'), 2) }}
                                                        </span>
                                                    @endif

                                                </span>
                                </summary>
                        @endfor
                        @break
                        @endif
                        @endforeach
                        @endforeach
                    </div>
                    <div x-show="tab === 2">
                        <div class="table-responsive">
                            <table class="table table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('common.name') }}</th>
                                    <th>{{ __('torrent.size') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($torrent->files as $k => $f)
                                    <tr>
                                        <td>{{ $k + 1 }}</td>
                                        <td>{{ $f->name }}</td>
                                        <td>{{ $f->getSize() }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">{{ __('common.close') }}</button>
            </div>
        </div>
    </div>
</div>

@if ($torrent->nfo != null)
    <div class="modal fade slideExpandUp" id="modal-10" role="dialog">
        <div class="modal-dialog modal-lg{{ modal_style() }}" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h4 class="modal-title" id="Modallabel3dsign">NFO</h4>
                </div>
                <div class="modal-body text-center">
                    <pre id="torrent_nfo" style="font-size:10pt; font-family: 'Courier New', monospace;">
                        {!! App\Helpers\Nfo::parseNfo($torrent->nfo) !!}
                    </pre>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal">{{ __('common.close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="modal fade" id="postpone-{{ $torrent->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <form method="POST" action="{{ route('staff.moderation.postpone') }}">
        @csrf
        <div class="modal-dialog{{ modal_style() }}">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('common.close') }}"><span
                                aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">{{ __('common.moderation-postpone') }}: {{ $torrent->name }}
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input id="type" name="type" type="hidden" value="{{ __('torrent.torrent') }}">
                        <input id="id" name="id" type="hidden" value="{{ $torrent->id }}">
                        <input id="slug" name="slug" type="hidden" value="{{ $torrent->slug }}">
                        <label for="postpone_reason" class="col-sm-2 control-label">{{ __('common.reason') }}</label>
                        <div class="col-sm-10">
                            <label for="message"></label>
                            <textarea title="{{ __('common.reason') }}" class="form-control" rows="5" name="message"
                                      cols="50" id="message"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <button class="btn btn-danger" type="submit">{{ __('common.moderation-postpone') }}</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary" data-dismiss="modal">{{ __('common.close') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="reject-{{ $torrent->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <form method="POST" action="{{ route('staff.moderation.reject') }}">
        @csrf
        <div class="modal-dialog{{ modal_style() }}">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('common.close') }}"><span
                                aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">{{ __('common.moderation-reject') }}: {{ $torrent->name }}
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input id="type" type="hidden" name="type" value="{{ __('torrent.torrent') }}">
                        <input id="id" type="hidden" name="id" value="{{ $torrent->id }}">
                        <input id="slug" type="hidden" name="slug" value="{{ $torrent->slug }}">
                        <label for="file_name" class="col-sm-2 control-label">{{ __('torrent.torrent') }}</label>
                        <div class="col-sm-10">
                            <label id="title" name="title" type="hidden">{{ $torrent->name }}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="report_reason" class="col-sm-2 control-label">{{ __('common.reason') }}</label>
                        <div class="col-sm-10">
                            <label for="message"></label>
                            <textarea title="{{ __('common.reason') }}" class="form-control" rows="5" name="message"
                                      cols="50" id="message"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <button class="btn btn-danger" type="submit">{{ __('common.moderation-reject') }}</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary" data-dismiss="modal">{{ __('common.close') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="modal_playlist_torrent" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog{{ modal_style() }}">
        <div class="modal-content">
            <div class="container-fluid">
                <form role="form" method="POST" action="{{ route('playlists.attach') }}">
                    @csrf
                    <input id="torrent_id" name="torrent_id" type="hidden" value="{{ $torrent->id }}">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Add Torrent To Playlist</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="playlist_id">Your Playlists</label>
                            <label>
                                <select name="playlist_id" class="form-control">
                                    @foreach ($playlists as $playlist)
                                        <option value="{{ $playlist->id }}">{{ $playlist->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-success" type="submit" value="Save">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary" type="button" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="panel panel-chat shoutbox">
    <div class="panel-heading">
        <h4>
            <i class="{{ config("other.font-awesome") }} fa-closed-captioning"></i> Subtitles
            <a href="{{ route('subtitles.create', ['torrent_id' => $torrent->id]) }}" class="btn btn-xs btn-primary" style="float: right;" title="Add subtitle">Add Subtitle</a>
        </h4>
    </div>

    <div class="table-responsive">
        <table class="table table-condensed table-bordered table-striped">
            <thead>
            <tr>
                <th>Language</th>
                <th>Download</th>
                <th>Extension</th>
                <th>Size</th>
                <th>Downloads</th>
                <th>Uploaded</th>
                <th>Uploader</th>
            </tr>
            </thead>
            <tbody>
            @foreach($torrent->subtitles as $subtitle)
                <tr>
                    <td>
                        {{ $subtitle->language->name }}
                        <i class="{{ config("other.font-awesome") }} fa-closed-captioning" data-toggle="tooltip" data-title="{{ $subtitle->note }}"></i>
                    </td>
                    <td>
                        <a href="{{ route('subtitles.download', ['id' => $subtitle->id]) }}" class="btn btn-xs btn-warning">Download</a>
                    </td>
                    <td>{{ $subtitle->extension }}</td>
                    <td>{{ $subtitle->getSize() }}</td>
                    <td>{{ $subtitle->downloads }}</td>
                    <td>{{ $subtitle->created_at->diffForHumans() }}</td>
                    <td>
                        @if ($subtitle->anon == true)
                        <span class="badge-user text-orange text-bold">{{ strtoupper(trans('common.anonymous')) }}
                            @if (auth()->user()->id == $subtitle->user_id || auth()->user()->group->is_modo)
                                <a href="{{ route('users.show', ['username' => $subtitle->user->username]) }}">
                                    ({{ $subtitle->user->username }})
                                </a>
                            @endif
                        </span>
                        @else
                        <a href="{{ route('users.show', ['username' => $subtitle->user->username]) }}">
                            <span class="badge-user text-bold" style="color:{{ $subtitle->user->group->color }}; background-image:{{ $subtitle->user->group->effect }};">
                                <i class="{{ $subtitle->user->group->icon }}" data-toggle="tooltip" data-original-title="{{ $subtitle->user->group->name }}"></i> {{ $subtitle->user->username }}
                            </span>
                        </a>
                        @endif

                        @if(auth()->user()->group->is_modo || auth()->user()->id == $subtitle->user->id)
                            <div class="align-right" style="display: inline-block;">
                                @include('subtitle.modals', ['subtitle' => $subtitle, 'torrent' => $torrent, 'media_languages' => App\Models\MediaLanguage::all()->sortBy('name')])
                                <a data-toggle="modal" data-target="#modal_edit_subtitle-{{ $subtitle->id }}" title="Edit Subtitle"><i class="fa fa-edit text-green"></i></a>
                                <a data-toggle="modal" data-target="#modal_delete_subtitle-{{ $subtitle->id }}" title="Delete Subtitle"><i class="fa fa-trash text-red"></i></a>
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="panel-footer text-center">
        <p>This torrent already includes the following subtitles muxed in:</p>
        @if ($text_crumbs !== null)
            @foreach ($text_crumbs as $key => $s)
                <span class="text-bold badge-extra">
                    <em>
                        @foreach ($s as $crumb)
                            {{ $crumb }}
                            @if (!$loop->last)
                                /
                            @endif
                        @endforeach
                    </em>
                </span>
            @endforeach
        @endif
    </div>
</div>
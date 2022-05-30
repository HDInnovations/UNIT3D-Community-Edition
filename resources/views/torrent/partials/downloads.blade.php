<div class="panel panel-chat shoutbox torrent-audits" x-data="{ show_downloads: false }">
    <div class="panel-heading">
        <h4 style="cursor: pointer;" @click="show_downloads = !show_downloads">
            <i class="{{ config("other.font-awesome") }} fa-clipboard-list"></i> Torrent File Downloads ({{ App\Models\TorrentDownload::where('torrent_id', '=', $torrent->id)->count() }} Total)
            <i class="{{ config("other.font-awesome") }} fa-plus-circle fa-pull-right" x-show="!show_downloads"></i>
            <i class="{{ config("other.font-awesome") }} fa-minus-circle fa-pull-right" x-show="show_downloads"></i>
        </h4>
    </div>

    <div class="table-responsive" x-show="show_downloads">
        <table class="table table-condensed table-bordered table-striped">
            <tbody>
            @foreach(App\Models\TorrentDownload::with(['user'])->where('torrent_id', '=', $torrent->id)->latest()->get() as $download)
                <tr>
                    <td>
                        <span>
                            <a href="{{ route('users.show', ['username' => $download->user->username]) }}">{{ $download->user->username }}</a> download this torrent file {{ $download->created_at->diffForHumans() }}
                            via {{ $download->type }}.
                        </span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

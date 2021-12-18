<div class="panel panel-chat shoutbox torrent-audits" x-data="{ show: false }">
    <div class="panel-heading">
        <h4 style="cursor: pointer;" @click="show = !show">
            <i class="{{ config("other.font-awesome") }} fa-clipboard-list"></i> Audits
            <i class="{{ config("other.font-awesome") }} fa-plus-circle fa-pull-right" x-show="!show"></i>
            <i class="{{ config("other.font-awesome") }} fa-minus-circle fa-pull-right" x-show="show"></i>
        </h4>
    </div>

    <div class="table-responsive" x-show="show">
        <table class="table table-condensed table-bordered table-striped">
            <tbody>
            @foreach(App\Models\Audit::where('model_entry_id', '=', $torrent->id)->where('model_name', '=', 'Torrent')->latest()->get() as $audit)
                @php $values = json_decode($audit->record, true) @endphp
                <tr>
                    <td>
                        <span>
                            {{ $audit->user->username }} {{ $audit->action }}d this torrent {{ $audit->created_at->diffForHumans() }}
                        </span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

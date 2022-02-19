<div class="panel panel-chat shoutbox torrent-bdinfo">
    <div class="panel-heading">
        <h4><i class="{{ config("other.font-awesome") }} fa-compact-disc"></i> BDInfo</h4>
    </div>
    <div class="table-responsive">
        <table class="table table-condensed table-bordered table-striped">
            <tbody>
            <tr>
                <td>
                    <div class="panel-body">
                        <pre class="decoda-code"><code>{{ $torrent->bdinfo }}</code></pre>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
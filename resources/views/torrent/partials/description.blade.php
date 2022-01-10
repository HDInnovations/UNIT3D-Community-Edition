<div class="panel panel-chat shoutbox torrent-description">
    <div class="panel-heading">
        <h4><i class="{{ config("other.font-awesome") }} fa-sticky-note"></i> {{ __('common.description') }}</h4>
    </div>
    <div class="table-responsive">
        <table class="table table-condensed table-bordered table-striped">
            <tbody>
            <tr>
                <td>
                    <div class="panel-body">
                        @joypixels($torrent->getDescriptionHtml())
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
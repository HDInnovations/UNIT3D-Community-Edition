<div class="col-md-12 col-sm-12">
    <div class="panel panel-chat shoutbox">
        <div class="panel-heading">
            <h4>
                <i class="{{ config('other.font-awesome') }} fa-comment"></i> {{ __('common.comments') }}
            </h4>
        </div>
        <div class="panel-body no-padding">
            <livewire:comments :model="$torrent"/>
        </div>
    </div>
</div>

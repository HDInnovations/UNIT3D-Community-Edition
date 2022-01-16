<div class="col-md-2">
    <div class="block">
        <a href="{{ route('create') }}" class="btn btn-primary btn-block">{{ __('pm.new') }}</a>
        <div class="separator"></div>
        <div class="list-group">
            <a href="{{ route('inbox') }}" class="btn btn-primary btn-block">{{ __('pm.inbox') }}</a>
            <a href="{{ route('outbox') }}" class="btn btn-primary btn-block">{{ __('pm.outbox') }}</a>
        </div>
    </div>
</div>

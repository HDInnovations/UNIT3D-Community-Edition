<div class="col-md-2">
    <div class="block">
        <a href="{{ route('create', ['username' => auth()->user()->username, 'id' => auth()->user()->id]) }}"
           class="btn btn-primary btn-block">{{ trans('pm.new') }}</a>
        <div class="separator"></div>
        <div class="list-group">
            <a href="{{ route('inbox', ['username' => auth()->user()->username, 'id' => auth()->user()->id]) }}"
               class="btn btn-primary btn-block">{{ trans('pm.inbox') }}</a>
            <a href="{{ route('outbox', ['username' => auth()->user()->username, 'id' => auth()->user()->id]) }}"
               class="btn btn-primary btn-block">{{ trans('pm.outbox') }}</a>
        </div>
    </div>
</div>
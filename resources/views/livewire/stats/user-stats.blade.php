<section class="panelV2 panel--grid-item">
    <h2 class="panel__heading">{{ __('common.users') }}</h2>
    <dl class="key-value">
        <div class="key-value__group">
            <dt>{{ __('stat.all') }} {{ __('common.users') }}</dt>
            <dd>{{ $all_user }}</dd>
        </div>
        <div class="key-value__group">
            <dt>{{ __('stat.active') }} {{ __('common.users') }}</dt>
            <dd>{{ $active_user }}</dd>
        </div>
        <div class="key-value__group">
            <dt>{{ __('stat.disabled') }} {{ __('common.users') }}</dt>
            <dd>{{ $disabled_user }}</dd>
        </div>
        <div class="key-value__group">
            <dt>{{ __('stat.pruned') }} {{ __('common.users') }}</dt>
            <dd>{{ $pruned_user }}</dd>
        </div>
        <div class="key-value__group">
            <dt>{{ __('stat.banned') }} {{ __('common.users') }}</dt>
            <dd>{{ $banned_user }}</dd>
        </div>
    </dl>
</section>

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
        <div class="key-value__group">
            <dt>Users active today</dt>
            <dd>{{ $users_active_today }}</dd>
        </div>
        <div class="key-value__group">
            <dt>Users active this week</dt>
            <dd>{{ $users_active_this_week }}</dd>
        </div>
        <div class="key-value__group">
            <dt>Users active this month</dt>
            <dd>{{ $users_active_this_month }}</dd>
        </div>
    </dl>
</section>

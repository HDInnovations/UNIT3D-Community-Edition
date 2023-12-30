@if ($poll &&$poll->voters()->where('user_id', '=', auth()->id())->doesntExist())
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('poll.poll') }}: {{ $poll->title }}</h2>
        <div class="panel__body">
            @include('poll.forms.vote')
        </div>
    </section>
@endif

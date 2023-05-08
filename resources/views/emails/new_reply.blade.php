@component('mail::message')
# {{ __('email.newreply-header') }}: {{ $topic->name }}
**{{ __('email.newreply-message') }}:**
<a href="{{ route('users.show', ['username' => $user->username]) }}">{{ $user->username }}</a>
{{ strtolower(__('email.newreply-replied')) }}
<a href="{{ route('topics.show', ['id' => $topic->id]) }}">{{ $topic->name }}</a>
@component('mail::button', ['url' => route('topics.show', ['id' => $topic->id]), 'color' => 'blue'])
{{ __('email.newreply-view') }}
@endcomponent
@endcomponent

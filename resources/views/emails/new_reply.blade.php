@component('mail::message')
# @lang('email.newreply-header'): {{ $topic->name }}
**@lang('email.newreply-message'):**
<a href="{{ route('users.show', ['username' => $user->username]) }}">{{ $user->username }}</a> {{ strtolower(trans('email.newreply-replied')) }}
<a href="{{ route('forum_topic', ['id' => $topic->id]) }}">{{ $topic->name }}</a>
@component('mail::button', ['url' => route('forum_topic', ['id' => $topic->id]), 'color' => 'blue'])
@lang('email.newreply-view')
@endcomponent
@endcomponent

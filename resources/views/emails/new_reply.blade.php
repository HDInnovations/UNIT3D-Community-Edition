@component('mail::message')
# Your topic: {{ $topic->name }} has a new reply!

**Message:** <a href="{{ route('profil', ['username' => $user->username, 'id' => $user->id]) }}">{{ $user->username }}</a> has replied to your topic
<a href="{{ route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id]) }}">{{ $topic->name }}</a>

@component('mail::button', ['url' => route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id]), 'color' => 'blue'])
View It Now
@endcomponent

@endcomponent

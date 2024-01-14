@props([
    'style',
    'anon',
    'appendedIcons',
    'user',
])

@if ($anon)
    @if (auth()->user()->is($user) || auth()->user()->group->is_modo)
        <span
            {{ $attributes->class('user-tag fas fa-eye-slash') }}
            {{ $attributes->merge(['style' => 'background-image: ' . $user->group->effect . ';' . ($style ?? '')]) }}
        >
            (
            <a
                class="user-tag__link user-tag__link--anonymous {{ $user->group->icon }}"
                href="{{ route('users.show', ['user' => $user]) }}"
                style="color: {{ $user->group->color }}"
                title="{{ $user->group->name }}"
            >
                {{ $user->username }}
            </a>
            {{ $appendedIcons ?? '' }}
            )
        </span>
    @else
        <span {{ $attributes->class('user-tag fas fa-eye-slash') }}>
            ({{ __('common.anonymous') }})
        </span>
    @endif
@else
    <span
        {{ $attributes->class('user-tag') }}
        {{ $attributes->merge(['style' => 'background-image: ' . $user->group->effect . ';' . ($style ?? '')]) }}
    >
        <a
            class="user-tag__link {{ $user->group->icon }}"
            href="{{ route('users.show', ['user' => $user]) }}"
            style="color: {{ $user->group->color }}"
            title="{{ $user->group->name }}"
        >
            {{ $user->username }}
        </a>
        {{ $appendedIcons ?? '' }}
    </span>
@endif

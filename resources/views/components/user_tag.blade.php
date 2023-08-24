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
            @if ($user->is_donor)
                {{ $attributes->merge(['style' => 'background-image: url(/img/sparkels.gif);' . ($style ?? '')] }}
            @endif
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
            @if ($user->is_donor)
                <i class="{{ config('other.font-awesome') }} fa-star text-gold" title="Donor"></i>
            @endif
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
        @if ($user->is_donor)
            {{ $attributes->merge(['style' => 'background-image: url(/img/sparkels.gif);' . ($style ?? '')] }}
        @endif
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
        @if ($user->is_donor)
            <i class="{{ config('other.font-awesome') }} fa-star text-gold" title="Donor"></i>
        @endif
        {{ $appendedIcons ?? '' }}
    </span>
@endif

<section class="panelV2">
    <h2 class="panel__heading">
        <i class="{{ config('other.font-awesome') }} fa-users"></i>
        {{ __('blocks.users-online') }} ({{ $users->count() }})
    </h2>
    <div class="panel__body">
        <ul style="display: flex; flex-wrap: wrap; column-gap: 1rem; list-style-type: none; padding: 0">
            @foreach ($users as $user)
                <li>
                    <x-user_tag :user="$user" :anon="$user->hidden || ! $user->isVisible($user, 'other', 'show_online')">
                        @if ($user->warnings_count > 0)
                            <x-slot:appended-icons>
                                <i
                                    class="{{ config('other.font-awesome') }} fa-exclamation-circle text-orange"
                                    title="{{ __('common.active-warning') }} ({{ $user->warnings_count }})"
                                ></i>
                            </x-slot>
                        @endif
                    </x-user_tag>
                </li>
            @endforeach
        </ul>
        <hr>
        <ul style="display: flex; flex-wrap: wrap; column-gap: 1rem; list-style-type: none; padding: 0; justify-content: center">
            @foreach ($groups as $group)
                <x-user_tag
                    :user="(object) [
                        'username' => $group->name,
                        'group'    => (object) [
                            'icon'   => $group->icon,
                            'color'  => $group->color,
                            'effect' => $group->effect,
                            'name'   => $group->name,
                        ]
                    ]"
                    :anon="false"
                />
            @endforeach
        </ul>
    </div>
</section>

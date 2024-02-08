<section class="panelV2 blocks__uploaders" x-data="{ tab: 'alltime' }">
    <h2 class="panel__heading">Top Uploaders</h2>
    <menu class="panel__tabs">
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === 'alltime' && 'panel__tab--active'"
            x-on:click="tab = 'alltime'"
        >
            All Time
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === '30days' && 'panel__tab--active'"
            x-on:click="tab = '30days'"
        >
            {{ __('stat.last30days') }}
        </li>
    </menu>
    <div class="data-table-wrapper" x-show="tab === 'alltime'">
        <table class="data-table">
            <thead>
                <tr>
                    <th>{{ __('common.user') }}</th>
                    <th>{{ __('user.total-uploads') }}</th>
                    <th>{{ __('stat.place') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($uploaders as $uploader)
                    <tr>
                        <td>
                            <x-user_tag
                                :user="$uploader->user"
                                :anon="$uploader->user->private_profile"
                            />
                        </td>
                        <td>{{ $uploader->value }}</td>
                        <td>
                            {{ App\Helpers\StringHelper::ordinal($loop->iteration) }}
                            {{ __('stat.place') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="data-table-wrapper" x-clock x-show="tab === '30days'">
        <table class="data-table">
            <thead>
                <tr>
                    <th>{{ __('common.user') }}</th>
                    <th>{{ __('user.total-uploads') }}</th>
                    <th>{{ __('stat.place') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($past_uploaders as $uploader)
                    <tr>
                        <td>
                            <x-user_tag
                                :user="$uploader->user"
                                :anon="$uploader->user->private_profile"
                            />
                        </td>
                        <td>{{ $uploader->value }}</td>
                        <td>
                            {{ App\Helpers\StringHelper::ordinal($loop->iteration) }}
                            {{ __('stat.place') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

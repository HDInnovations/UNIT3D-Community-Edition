<div style="display: flex; flex-direction: column; row-gap: 1rem">
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('common.search') }}</h2>
        </header>
        <div class="panel__body" style="padding: 5px">
            <form class="form">
                <div class="form__group--short-horizontal">
                    <p class="form__group">
                        <select
                            id="type"
                            wire:model.live="type"
                            class="form__select"
                            placeholder=" "
                        >
                            <option value="" selected>All</option>
                            <option value="Torrent">Torrent</option>
                            <option value="Request">Request</option>
                            <option value="User">User</option>
                        </select>
                        <label class="form__label form__label--floating" for="type">
                            Type
                        </label>
                    </p>
                    <p class="form__group">
                        <input
                            id="title"
                            class="form__text"
                            type="text"
                            wire:model.live="title"
                            placeholder=" "
                        />
                        <label class="form__label form__label--floating" for="title">
                            {{ __('torrent.title') }}
                        </label>
                    </p>
                    <p class="form__group">
                        <input
                            id="reported"
                            class="form__text"
                            type="text"
                            wire:model.live="reported"
                            placeholder=" "
                        />
                        <label class="form__label form__label--floating" for="reported">
                            Reported
                        </label>
                    </p>
                    <p class="form__group">
                        <input
                            id="reporter"
                            class="form__text"
                            type="text"
                            wire:model.live="reporter"
                            placeholder=" "
                        />
                        <label class="form__label form__label--floating" for="reporter">
                            {{ __('common.reporter') }}
                        </label>
                    </p>
                    <p class="form__group">
                        <input
                            id="judge"
                            class="form__text"
                            type="text"
                            wire:model.live="judge"
                            placeholder=" "
                        />
                        <label class="form__label form__label--floating" for="judge">
                            {{ __('user.judge') }}
                        </label>
                    </p>
                    <p class="form__group">
                        <select
                            id="quantity"
                            class="form__select"
                            wire:model.live="perPage"
                            required
                        >
                            <option>25</option>
                            <option>50</option>
                            <option>100</option>
                        </select>
                        <label class="form__label form__label--floating" for="quantity">
                            {{ __('common.quantity') }}
                        </label>
                    </p>
                </div>
            </form>
        </div>
    </section>
    <div>
        <section class="panelV2">
            <h2 class="panel__heading">{{ __('staff.reports-log') }}</h2>
            <div class="data-table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>{{ __('common.title') }}</th>
                            <th>Reported</th>
                            <th>{{ __('common.reporter') }}</th>
                            <th>{{ __('user.created-on') }}</th>
                            <th>{{ __('user.judge') }}</th>
                            <th>{{ __('forum.solved') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $report)
                            <tr>
                                <td>{{ $report->id }}</td>
                                <td>{{ $report->type }}</td>
                                <td>
                                    <a href="{{ route('staff.reports.show', ['report' => $report]) }}">
                                        {{ $report->title }}
                                    </a>
                                </td>
                                <td>
                                    <x-user_tag :anon="false" :user="$report->reported" />
                                </td>
                                <td>
                                    <x-user_tag :anon="false" :user="$report->reporter" />
                                </td>
                                <td>
                                    <time
                                        datetime="{{ $report->created_at }}"
                                        title="{{ $report->created_at }}"
                                    >
                                        {{ $report->created_at->toDayDateTimeString() }}
                                    </time>
                                </td>
                                <td>
                                    @if ($report->staff_id !== null)
                                        <x-user_tag :anon="false" :user="$report->staff" />
                                    @endif
                                </td>
                                <td>
                                    @if ($report->solved)
                                        <i
                                            class="{{ config('other.font-awesome') }} fa-check text-green"
                                        ></i>
                                        {{ __('common.yes') }}
                                    @else
                                        <i
                                            class="{{ config('other.font-awesome') }} fa-times text-red"
                                        ></i>
                                        {{ __('common.no') }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">No reports</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $reports->links('partials.pagination') }}
        </section>
    </div>
</div>
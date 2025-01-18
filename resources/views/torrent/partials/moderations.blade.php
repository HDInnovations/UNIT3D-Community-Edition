<div class="panelV2">
    <h2 class="panel__heading">
        <i class="{{ config('other.font-awesome') }} fa-clipboard-list"></i>
        {{ __('torrent.moderation') }} {{ __('pm.messages') }}
    </h2>
    <div class="data-table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>{{ __('common.moderated-by') }}</th>
                    <th>{{ __('torrent.status') }}</th>
                    <th>{{ __('pm.messages') }}</th>
                    <th>{{ __('common.date') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($torrent->moderationMessages as $message)
                    <tr>
                        <td><x-user_tag :user="$message->moderator" :anon="false" /></td>
                        <td>
                            @switch($message->status)
                                @case(\App\Models\Torrent::APPROVED)
                                    <p class="text-green">{{ __('torrent.approved') }}</p>

                                    @break
                                @case(\App\Models\Torrent::POSTPONED)
                                    <p class="text-orange">{{ __('torrent.postponed') }}</p>

                                    @break
                                @case(\App\Models\Torrent::REJECTED)
                                    <p class="text-red">{{ __('torrent.rejected') }}</p>

                                    @break
                                @default
                                    N/A
                            @endswitch
                        </td>
                        <td>{{ $message->message ?? 'N/A' }}</td>
                        <td>{{ $message->created_at }}</td>
                    </tr>
                @empty
                    No moderations found.
                @endforelse
            </tbody>
        </table>
    </div>
</div>

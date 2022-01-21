<div class="table-responsive">
    <table class="table table-condensed table-striped table-bordered">
        <thead>
        <th>{{ __('torrent.name') }}</th>
        <th>{{ __('torrent.client') }}</th>
        <th>{{ __('torrent.size') }}</th>
        <th>{{ __('torrent.seeders') }}</th>
        <th>{{ __('torrent.leechers') }}</th>
        <th>{{ __('common.upload') }}</th>
        <th>{{ __('common.download') }}</th>
        <th>{{ __('torrent.remaining') }}</th>
        <th>{{ __('torrent.progress') }}</th>
        </thead>
        <tbody>
        @foreach ($active as $p)
            <tr>
                <td>
                    <a class="view-torrent" href="{{ route('torrent', ['id' => $p->torrent_id]) }}"
                       data-toggle="tooltip" title="{{ $p->torrent->name }}">
                        {{ $p->torrent->name }}
                    </a>
                </td>
                <td>
                        <span
                                class="badge-extra text-purple text-bold">{{ $p->agent ?: __('common.unknown') }}</span>
                </td>
                <td>
                    <span class="badge-extra text-blue text-bold"> {{ $p->torrent->getSize() }}</span>
                </td>
                <td>
                    <span class="badge-extra text-green text-bold"> {{ $p->torrent->seeders }}</span>
                </td>
                <td>
                    <span class="badge-extra text-red text-bold"> {{ $p->torrent->leechers }}</span>
                </td>
                <td>
                        <span class="badge-extra text-green text-bold">
                            {{ App\Helpers\StringHelper::formatBytes($p->uploaded, 2) }}</span>
                </td>
                <td>
                        <span class="badge-extra text-red text-bold">
                            {{ App\Helpers\StringHelper::formatBytes($p->downloaded, 2) }}</span>
                </td>
                <td>
                        <span
                                class="badge-extra text-orange text-bold">{{ \App\Helpers\StringHelper::formatBytes($p->left, 2) }}</span>
                </td>
                @if ($p->seeder == 0)
                    <td>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active" role="progressbar"
                                 aria-valuenow="{{ ($p->downloaded / $p->torrent->size) * 100 }}" aria-valuemin="0"
                                 aria-valuemax="100" style="width: {{ ($p->downloaded / $p->torrent->size) * 100 }}%;">
                                {{ round(($p->downloaded / $p->torrent->size) * 100) }}%
                            </div>
                        </div>
                    </td>
                @elseif ($p->seeder == 1)
                    <td>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100"
                                 aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                100%
                            </div>
                        </div>
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{ $active->links() }}
    </div>
</div>

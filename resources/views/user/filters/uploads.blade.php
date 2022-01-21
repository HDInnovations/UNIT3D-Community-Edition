<div class="block">
    <div class="table-responsive">
        <table class="table table-condensed table-striped table-bordered">
            <thead>
            <th>{{ __('torrent.name') }}</th>
            <th>{{ __('torrent.category') }}</th>
            <th>{{ __('torrent.size') }}</th>
            <th>{{ __('torrent.seeders') }}</th>
            <th>{{ __('torrent.leechers') }}</th>
            <th>{{ __('torrent.completed') }}</th>
            <th>{{ __('torrent.bon-tipped') }}</th>
            <th>{{ __('torrent.thanked') }}</th>
            <th>{{ __('torrent.created_at') }}</th>
            <th>{{ __('torrent.moderation') }}</th>
            <th>{{ __('torrent.status') }}</th>
            </thead>
            <tbody>
            @foreach ($uploads as $upload)
                <tr>
                    <td>
                        <a class="view-torrent" href="{{ route('torrent', ['id' => $upload->id]) }}">
                            {{ $upload->name }}
                        </a>
                        <div class="pull-right">
                            <a href="{{ route('download', ['id' => $upload->id]) }}">
                                <button class="btn btn-primary btn-circle" type="button"><i
                                            class="{{ config('other.font-awesome') }} fa-download"></i></button>
                            </a>
                        </div>
                    </td>
                    <td>
                        {{ $upload->category->name }}
                    </td>
                    <td>
                        <span class="badge-extra text-blue text-bold"> {{ $upload->getSize() }}</span>
                    </td>
                    <td>
                        <span class="badge-extra text-green text-bold"> {{ $upload->seeders }}</span>
                    </td>
                    <td>
                        <span class="badge-extra text-red text-bold"> {{ $upload->leechers }}</span>
                    </td>
                    <td>
                            <span class="badge-extra text-orange text-bold"> {{ $upload->times_completed }}
                                {{ __('common.times') }}</span>
                    </td>
                    <td>
                            <span class="badge-extra text-green text-bold">
                                {{ $upload->tipped_total ?: 'N/A' }}</span>
                    </td>
                    <td>
                            <span class="badge-extra text-red text-bold">
                                {{ $upload->thanked_total ?: 'N/A' }}</span>
                    </td>
                    <td>{{ $upload->created_at ? $upload->created_at->diffForHumans() : 'N/A' }}</td>
                    <td>
                        @if ($upload->isPending())
                            <span class='label label-warning' data-toggle="tooltip">{{ __('torrent.pending') }}</span>
                        @elseif ($upload->isApproved())
                            <span class='label label-success' data-toggle="tooltip"
                                  data-original-title="Moderated By {{ $upload->moderated->username }} {{ $upload->moderated_at->diffForHumans() }}">{{ __('torrent.approved') }}</span>
                        @elseif ($upload->isRejected())
                            <span class='label label-danger' data-toggle="tooltip"
                                  data-original-title="Moderated By {{ $upload->moderated->username }} {{ $upload->moderated_at->diffForHumans() }}">{{ __('torrent.rejected') }}</span>
                        @endif
                    </td>
                    <td>
                        @if ($upload->seeders + $upload->leechers == 0)
                            <span class='label label-danger'>{{ __('graveyard.dead') }}</span>
                        @elseif ($upload->seeders >= 1)
                            <span class='label label-success'>{{ __('torrent.alive') }}</span>
                        @elseif ($upload->leechers >= 1 + $upload->seeders = 0)
                            <span class='label label-info'>{{ __('torrent.requires-reseed') }}</span>
                        @else
                            <span class='label label-warning'>{{ strtoupper(__('common.error')) }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="text-center">
            {{ $uploads->appends(request()->except('page'))->render() }}
        </div>
    </div>
</div>

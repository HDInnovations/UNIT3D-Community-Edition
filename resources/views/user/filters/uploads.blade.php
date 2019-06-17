<div class="block">
    <!-- History -->
    <div class="table-responsive">
        <table class="table table-condensed table-striped table-bordered">
            <thead>
            <th>@lang('torrent.name')</th>
            <th>@lang('torrent.category')</th>
            <th>@lang('torrent.size')</th>
            <th>@lang('torrent.seeders')</th>
            <th>@lang('torrent.leechers')</th>
            <th>@lang('torrent.completed')</th>
            <th>@lang('torrent.bon-tipped')</th>
            <th>@lang('torrent.thanked')</th>
            <th>@lang('torrent.created_at')</th>
            <th>@lang('torrent.moderation')</th>
            <th>@lang('torrent.status')</th>
            </thead>
            <tbody>
            @foreach ($uploads as $upload)
                <tr>
                    <td>
                        <a class="view-torrent" href="{{ route('torrent', ['slug' => $upload->slug, 'id' => $upload->id]) }}">
                            {{ $upload->name }}
                        </a>
                        <div class="pull-right">
                            <a href="{{ route('download', ['slug' => $upload->slug, 'id' => $upload->id]) }}">
                                <button class="btn btn-primary btn-circle" type="button"><i
                                            class="{{ config('other.font-awesome') }} fa-download"></i></button>
                            </a>
                        </div>
                    </td>
                    <td>
                        <a href="{{ route('category', ['slug' => $upload->category->slug, 'id' => $upload->category->id]) }}">{{ $upload->category->name }}</a>
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
                        <span class="badge-extra text-orange text-bold"> {{ $upload->times_completed }} @lang('common.times')</span>
                    </td>
                    <td>
                        <span class="badge-extra text-green text-bold"> {{ ( $upload->tipped_total ? $upload->tipped_total : 'N/A' ) }}</span>
                    </td>
                    <td>
                        <span class="badge-extra text-red text-bold"> {{ ( $upload->thanked_total ? $upload->thanked_total : 'N/A' ) }}</span>
                    </td>
                    <td>{{ ($upload->created_at ? $upload->created_at->diffForHumans() : 'N/A') }}</td>
                    <td>
                        @if ($upload->isPending())
                            <span class='label label-warning' data-toggle="tooltip">@lang('torrent.pending')</span>
                        @elseif ($upload->isApproved())
                            <span class='label label-success' data-toggle="tooltip"
                                  data-original-title="Moderated By {{ $upload->moderated->username }} {{ $upload->moderated_at->diffForHumans() }}">@lang('torrent.approved')</span>
                        @elseif ($upload->isRejected())
                            <span class='label label-danger' data-toggle="tooltip"
                                  data-original-title="Moderated By {{ $upload->moderated->username }} {{ $upload->moderated_at->diffForHumans() }}">@lang('torrent.rejected')</span>
                        @endif
                    </td>
                    <td>
                        @if ($upload->seeders + $upload->leechers == 0)
                            <span class='label label-danger'>@lang('graveyard.dead')</span>
                        @elseif ($upload->seeders >= 1)
                            <span class='label label-success'>@lang('torrent.alive')</span>
                        @elseif ($upload->leechers >= 1 + $upload->seeders = 0)
                            <span class='label label-info'>@lang('torrent.requires-reseed')</span>
                        @else
                            <span class='label label-warning'>{{ strtoupper(trans('common.error')) }}</span>
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

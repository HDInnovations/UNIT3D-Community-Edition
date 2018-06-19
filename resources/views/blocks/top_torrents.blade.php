<div class="col-md-10 col-sm-10 col-md-offset-1">
    <div class="clearfix visible-sm-block"></div>
    <div class="panel panel-chat shoutbox">
        <div class="panel-heading">
            <h4>{{ trans('blocks.latest-torrents') }}</h4>
        </div>
        <ul class="nav nav-tabs mb-5" role="tablist">
            <li class="active"><a href="#newtorrents" role="tab" data-toggle="tab" aria-expanded="true"><i
                            class="fa fa-trophy text-gold"></i> {{ trans('blocks.new-torrents') }}</a></li>
            <li class=""><a href="#topseeded" role="tab" data-toggle="tab" aria-expanded="false"><i
                            class="fa fa-arrow-up text-success"></i> {{ trans('torrent.top-seeded') }}</a></li>
            <li class=""><a href="#topleeched" role="tab" data-toggle="tab" aria-expanded="false"><i
                            class="fa fa-arrow-down text-danger"></i> {{ trans('torrent.top-leeched') }}</a></li>
            <li class=""><a href="#dyingtorrents" role="tab" data-toggle="tab" aria-expanded="false"><i
                            class="fa fa-arrow-down text-red"></i> {{ trans('torrent.dying-torrents') }}
                    <i class="fa fa-recycle text-red" data-toggle="tooltip" title=""
                       data-original-title="{{ trans('blocks.requires-reseed') }}"></i></a></li>
            <li class=""><a href="#deadtorrents" role="tab" data-toggle="tab" aria-expanded="false"><i
                            class="fa fa-exclamation-triangle text-red"></i> {{ trans('torrent.dead-torrents') }}
                    <i class="fa fa-recycle text-red" data-toggle="tooltip" title=""
                       data-original-title="{{ trans('blocks.requires-reseed') }}"></i></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="newtorrents">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                        <tr>
                            <th class="torrents-icon"></th>
                            <th class="torrents-filename">{{ trans('torrent.name') }}</th>
                            <th>{{ trans('torrent.age') }}</th>
                            <th>{{ trans('torrent.size') }}</th>
                            <th>{{ trans('torrent.short-seeds') }}</th>
                            <th>{{ trans('torrent.short-leechs') }}</th>
                            <th>{{ trans('torrent.short-completed') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($torrents as $t)
                            <tr class="">
                                <td><i class="{{ $t->category->icon }} torrent-icon" data-toggle="tooltip" title=""
                                       data-original-title="{{ $t->category->name }} torrent"></i></td>
                                <td>
                                    <div class="torrent-file">
                                        <div>
                                            <a href="{{ route('torrent', ['slug' => $t->slug, 'id' => $t->id]) }}"
                                               class="" title="">
                                                {{ $t->name }}
                                            </a>
                                        </div>
                                        <div>
                                            <span class="badge-extra">{{ $t->type }}</span>&nbsp;&nbsp;
                                            @if($t->stream == "1")<span class="badge-extra"><i
                                                        class="fa fa-play text-red text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('torrent.stream-optimized') }}"></i> {{ trans('torrent.stream-optimized') }}</span> @endif
                                            @if($t->doubleup == "1")<span class="badge-extra"><i
                                                        class="fa fa-diamond text-green text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('torrent.double-upload') }}"></i> {{ trans('torrent.double-upload') }}</span> @endif
                                            @if($t->free == "1")<span class="badge-extra"><i
                                                        class="fa fa-star text-gold text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('torrent.freeleech') }}"></i> {{ trans('torrent.freeleech') }}</span> @endif
                                            @if(config('other.freeleech') == true)<span class="badge-extra"><i
                                                        class="fa fa-globe text-blue text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('common.global') }} {{ trans('torrent.freeleech') }}"></i> {{ trans('common.global') }} {{ trans('torrent.freeleech') }}</span> @endif
                                            @if(config('other.doubleup') == true)<span class="badge-extra"><i
                                                        class="fa fa-globe text-green text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('common.global') }} {{ trans('torrent.double-upload') }}"></i> {{ trans('common.global') }} {{ trans('torrent.double-upload') }}</span> @endif
                                            @if($t->leechers >= "5") <span class="badge-extra"><i
                                                        class="fa fa-fire text-orange text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('common.hot') }}"></i> {{ trans('common.hot') }}</span> @endif
                                            @if($t->sticky == 1) <span class="badge-extra"><i
                                                        class="fa fa-thumb-tack text-black text-bold"
                                                        data-toggle="tooltip" title=""
                                                        data-original-title="{{ trans('common.sticked') }}"></i> {{ trans('common.sticked') }}</span> @endif
                                            @if($t->highspeed == 1)<span class="badge-extra"><i
                                                        class="fa fa-tachometer text-red text-bold"
                                                        data-toggle="tooltip" title=""
                                                        data-original-title="{{ trans('common.high-speeds') }}"></i> {{ trans('common.high-speeds') }}</span> @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span data-toggle="tooltip" title=""
                                          data-original-title="">{{ $t->created_at->diffForHumans() }}</span>
                                </td>
                                <td>
                                    <span class="">{{ $t->getSize() }}</span>
                                </td>
                                <td>{{ $t->seeders }}</td>
                                <td>{{ $t->leechers }}</td>
                                <td>{{ $t->times_completed }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="topseeded">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                        <tr>
                            <th class="torrents-icon"></th>
                            <th class="torrents-filename">{{ trans('torrent.name') }}</th>
                            <th>{{ trans('torrent.age') }}</th>
                            <th>{{ trans('torrent.size') }}</th>
                            <th>{{ trans('torrent.short-seeds') }}</th>
                            <th>{{ trans('torrent.short-leechs') }}</th>
                            <th>{{ trans('torrent.short-completed') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($best as $b)
                            <tr class="">
                                <td><i class="{{ $b->category->icon }} torrent-icon" data-toggle="tooltip" title=""
                                       data-original-title="{{ $b->category->name }} Torrent"></i></td>
                                <td>
                                    <div class="torrent-file">
                                        <div>
                                            <a href="{{ route('torrent', ['slug' => $b->slug, 'id' => $b->id]) }}"
                                               class="" title="">
                                                {{ $b->name }}
                                            </a>
                                        </div>
                                        <div>
                                            <span class="badge-extra">{{ $b->type }}</span>&nbsp;&nbsp;
                                            @if($b->stream == "1")<span class="badge-extra"><i
                                                        class="fa fa-play text-red text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('torrent.stream-optimized') }}"></i> {{ trans('torrent.stream-optimized') }}</span> @endif
                                            @if($b->doubleup == "1")<span class="badge-extra"><i
                                                        class="fa fa-diamond text-green text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('torrent.double-upload') }}"></i> {{ trans('torrent.double-upload') }}</span> @endif
                                            @if($b->free == "1")<span class="badge-extra"><i
                                                        class="fa fa-star text-gold text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('torrent.freeleech') }}"></i> {{ trans('torrent.freeleech') }}</span> @endif
                                            @if(config('other.freeleech') == true)<span class="badge-extra"><i
                                                        class="fa fa-globe text-blue text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('common.global') }} {{ trans('torrent.freeleech') }}"></i> {{ trans('common.global') }} {{ trans('torrent.freeleech') }}</span> @endif
                                            @if(config('other.doubleup') == true)<span class="badge-extra"><i
                                                        class="fa fa-globe text-green text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('common.global') }} {{ trans('torrent.double-upload') }}"></i> {{ trans('common.global') }} {{ trans('torrent.double-upload') }}</span> @endif
                                            @if($b->leechers >= "5") <span class="badge-extra"><i
                                                        class="fa fa-fire text-orange text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('common.hot') }}"></i> {{ trans('common.hot') }}</span> @endif
                                            @if($b->sticky == 1) <span class="badge-extra"><i
                                                        class="fa fa-thumb-tack text-black text-bold"
                                                        data-toggle="tooltip" title=""
                                                        data-original-title="{{ trans('common.sticked') }}"></i> {{ trans('common.sticked') }}</span> @endif
                                            @if($b->highspeed == 1)<span class="badge-extra"><i
                                                        class="fa fa-tachometer text-red text-bold"
                                                        data-toggle="tooltip" title=""
                                                        data-original-title="{{ trans('common.high-speeds') }}"></i> {{ trans('common.high-speeds') }}</span> @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span data-toggle="tooltip" title=""
                                          data-original-title="">{{ $b->created_at->diffForHumans() }}</span>
                                </td>
                                <td>
                                    <span class="">{{ $b->getSize() }}</span>
                                </td>
                                <td>{{ $b->seeders }}</td>
                                <td>{{ $b->leechers }}</td>
                                <td>{{ $b->times_completed }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="topleeched">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                        <tr>
                            <th class="torrents-icon"></th>
                            <th class="torrents-filename">{{ trans('torrent.name') }}</th>
                            <th>{{ trans('torrent.age') }}</th>
                            <th>{{ trans('torrent.size') }}</th>
                            <th>{{ trans('torrent.short-seeds') }}</th>
                            <th>{{ trans('torrent.short-leechs') }}</th>
                            <th>{{ trans('torrent.short-completed') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($leeched as $l)
                            <tr class="">
                                <td><i class="{{ $l->category->icon }} torrent-icon" data-toggle="tooltip" title=""
                                       data-original-title="{{ $l->category->name }} Torrent"></i></td>
                                <td>
                                    <div class="torrent-file">
                                        <div>
                                            <a href="{{ route('torrent', ['slug' => $l->slug, 'id' => $l->id]) }}"
                                               class="" title="">
                                                {{ $l->name }}
                                            </a>
                                        </div>
                                        <div>
                                            <span class="badge-extra">{{ $l->type }}</span>&nbsp;&nbsp;
                                            @if($l->stream == "1")<span class="badge-extra"><i
                                                        class="fa fa-play text-red text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('torrent.stream-optimized') }}"></i> {{ trans('torrent.stream-optimized') }}</span> @endif
                                            @if($l->doubleup == "1")<span class="badge-extra"><i
                                                        class="fa fa-diamond text-green text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('torrent.double-upload') }}"></i> {{ trans('torrent.double-upload') }}</span> @endif
                                            @if($l->free == "1")<span class="badge-extra"><i
                                                        class="fa fa-star text-gold text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('torrent.freeleech') }}"></i> {{ trans('torrent.freeleech') }}</span> @endif
                                            @if(config('other.freeleech') == true)<span class="badge-extra"><i
                                                        class="fa fa-globe text-blue text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('common.global') }} {{ trans('torrent.freeleech') }}"></i> {{ trans('common.global') }} {{ trans('torrent.freeleech') }}</span> @endif
                                            @if(config('other.doubleup') == true)<span class="badge-extra"><i
                                                        class="fa fa-globe text-green text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('common.global') }} {{ trans('torrent.double-upload') }}"></i> {{ trans('common.global') }} {{ trans('torrent.double-upload') }}</span> @endif
                                            @if($l->leechers >= "5") <span class="badge-extra"><i
                                                        class="fa fa-fire text-orange text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('common.hot') }}"></i> {{ trans('common.hot') }}</span> @endif
                                            @if($l->sticky == 1) <span class="badge-extra"><i
                                                        class="fa fa-thumb-tack text-black text-bold"
                                                        data-toggle="tooltip" title=""
                                                        data-original-title="{{ trans('common.sticked') }}"></i> {{ trans('common.sticked') }}</span> @endif
                                            @if($l->highspeed == 1)<span class="badge-extra"><i
                                                        class="fa fa-tachometer text-red text-bold"
                                                        data-toggle="tooltip" title=""
                                                        data-original-title="{{ trans('common.high-speeds') }}"></i> {{ trans('common.high-speeds') }}</span> @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span data-toggle="tooltip" title=""
                                          data-original-title="">{{ $l->created_at->diffForHumans() }}</span>
                                </td>
                                <td>
                                    <span class="">{{ $l->getSize() }}</span>
                                </td>
                                <td>{{ $l->seeders }}</td>
                                <td>{{ $l->leechers }}</td>
                                <td>{{ $l->times_completed }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="dyingtorrents">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                        <tr>
                            <th class="torrents-icon"></th>
                            <th class="torrents-filename">{{ trans('torrent.name') }}</th>
                            <th>{{ trans('torrent.age') }}</th>
                            <th>{{ trans('torrent.size') }}</th>
                            <th>{{ trans('torrent.short-seeds') }}</th>
                            <th>{{ trans('torrent.short-leechs') }}</th>
                            <th>{{ trans('torrent.short-completed') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($dying as $d)
                            <tr class="">
                                <td><i class="{{ $d->category->icon }} torrent-icon" data-toggle="tooltip" title=""
                                       data-original-title="{{ $d->category->name }} Torrent"></i></td>
                                <td>
                                    <div class="torrent-file">
                                        <div>
                                            <a href="{{ route('torrent', ['slug' => $d->slug, 'id' => $d->id]) }}"
                                               class="" title="">
                                                {{ $d->name }}
                                            </a>
                                        </div>
                                        <div>
                                            <span class="badge-extra">{{ $d->type }}</span>&nbsp;&nbsp;
                                            @if($d->stream == "1")<span class="badge-extra"><i
                                                        class="fa fa-play text-red text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('torrent.stream-optimized') }}"></i> {{ trans('torrent.stream-optimized') }}</span> @endif
                                            @if($d->doubleup == "1")<span class="badge-extra"><i
                                                        class="fa fa-diamond text-green text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('torrent.double-upload') }}"></i> {{ trans('torrent.double-upload') }}</span> @endif
                                            @if($d->free == "1")<span class="badge-extra"><i
                                                        class="fa fa-star text-gold text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('torrent.freeleech') }}"></i> {{ trans('torrent.freeleech') }}</span> @endif
                                            @if(config('other.freeleech') == true)<span class="badge-extra"><i
                                                        class="fa fa-globe text-blue text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('common.global') }} {{ trans('torrent.freeleech') }}"></i> {{ trans('common.global') }} {{ trans('torrent.freeleech') }}</span> @endif
                                            @if(config('other.doubleup') == true)<span class="badge-extra"><i
                                                        class="fa fa-globe text-green text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('common.global') }} {{ trans('torrent.double-upload') }}"></i> {{ trans('common.global') }} {{ trans('torrent.double-upload') }}</span> @endif
                                            @if($d->leechers >= "5") <span class="badge-extra"><i
                                                        class="fa fa-fire text-orange text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('common.hot') }}"></i> {{ trans('common.hot') }}</span> @endif
                                            @if($d->sticky == 1) <span class="badge-extra"><i
                                                        class="fa fa-thumb-tack text-black text-bold"
                                                        data-toggle="tooltip" title=""
                                                        data-original-title="{{ trans('common.sticked') }}"></i> {{ trans('common.sticked') }}</span> @endif
                                            @if($d->highspeed == 1)<span class="badge-extra"><i
                                                        class="fa fa-tachometer text-red text-bold"
                                                        data-toggle="tooltip" title=""
                                                        data-original-title="{{ trans('common.high-speeds') }}"></i> {{ trans('common.high-speeds') }}</span> @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span data-toggle="tooltip" title=""
                                          data-original-title="">{{ $d->created_at->diffForHumans() }}</span>
                                </td>
                                <td>
                                    <span class="">{{ $d->getSize() }}</span>
                                </td>
                                <td>{{ $d->seeders }}</td>
                                <td>{{ $d->leechers }}</td>
                                <td>{{ $d->times_completed }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="deadtorrents">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                        <tr>
                            <th class="torrents-icon"></th>
                            <th class="torrents-filename">{{ trans('torrent.name') }}</th>
                            <th>{{ trans('torrent.age') }}</th>
                            <th>{{ trans('torrent.size') }}</th>
                            <th>{{ trans('torrent.short-seeds') }}</th>
                            <th>{{ trans('torrent.short-leechs') }}</th>
                            <th>{{ trans('torrent.short-completed') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($dead as $d)
                            <tr class="">
                                <td><i class="{{ $d->category->icon }} torrent-icon" data-toggle="tooltip" title=""
                                       data-original-title="{{ $d->category->name }} Torrent"></i></td>
                                <td>
                                    <div class="torrent-file">
                                        <div>
                                            <a href="{{ route('torrent', ['slug' => $d->slug, 'id' => $d->id]) }}"
                                               class="" title="">
                                                {{ $d->name }}
                                            </a>
                                        </div>
                                        <div>
                                            <span class="badge-extra">{{ $d->type }}</span>&nbsp;&nbsp;
                                            @if($d->stream == "1")<span class="badge-extra"><i
                                                        class="fa fa-play text-red text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('torrent.stream-optimized') }}"></i> {{ trans('torrent.stream-optimized') }}</span> @endif
                                            @if($d->doubleup == "1")<span class="badge-extra"><i
                                                        class="fa fa-diamond text-green text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('torrent.double-upload') }}"></i> {{ trans('torrent.double-upload') }}</span> @endif
                                            @if($d->free == "1")<span class="badge-extra"><i
                                                        class="fa fa-star text-gold text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('torrent.freeleech') }}"></i> {{ trans('torrent.freeleech') }}</span> @endif
                                            @if(config('other.freeleech') == true)<span class="badge-extra"><i
                                                        class="fa fa-globe text-blue text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('common.global') }} {{ trans('torrent.freeleech') }}"></i> {{ trans('common.global') }} {{ trans('torrent.freeleech') }}</span> @endif
                                            @if(config('other.doubleup') == true)<span class="badge-extra"><i
                                                        class="fa fa-globe text-green text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('common.global') }} {{ trans('torrent.double-upload') }}"></i> {{ trans('common.global') }} {{ trans('torrent.double-upload') }}</span> @endif
                                            @if($d->leechers >= "5") <span class="badge-extra"><i
                                                        class="fa fa-fire text-orange text-bold" data-toggle="tooltip"
                                                        title=""
                                                        data-original-title="{{ trans('common.hot') }}"></i> {{ trans('common.hot') }}</span> @endif
                                            @if($d->sticky == 1) <span class="badge-extra"><i
                                                        class="fa fa-thumb-tack text-black text-bold"
                                                        data-toggle="tooltip" title=""
                                                        data-original-title="{{ trans('common.sticked') }}"></i> {{ trans('common.sticked') }}</span> @endif
                                            @if($d->highspeed == 1)<span class="badge-extra"><i
                                                        class="fa fa-tachometer text-red text-bold"
                                                        data-toggle="tooltip" title=""
                                                        data-original-title="{{ trans('common.high-speeds') }}"></i> {{ trans('common.high-speeds') }}</span> @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span data-toggle="tooltip" title=""
                                          data-original-title="">{{ $d->created_at->diffForHumans() }}</span>
                                </td>
                                <td>
                                    <span class="">{{ $d->getSize() }}</span>
                                </td>
                                <td>{{ $d->seeders }}</td>
                                <td>{{ $d->leechers }}</td>
                                <td>{{ $d->times_completed }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-10 col-sm-10 col-md-offset-1">
  <div class="clearfix visible-sm-block"></div>
  <div class="panel panel-chat shoutbox">
    <div class="panel-heading">
      <h4>{{ trans('blocks.latest-torrents') }}</h4>
    </div>
    <ul class="nav nav-tabs mb-5" role="tablist">
      <li class="active"><a href="#newtorrents" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-trophy text-gold"></i> New Torrents</a></li>
      <li class=""><a href="#topseeded" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-arrow-up text-success"></i> Top Seeded</a></li>
      <li class=""><a href="#topleeched" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-arrow-down text-danger"></i> Top Leeched</a></li>
      <li class=""><a href="#dyingtorrents" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-arrow-down text-red"></i> Dying Torrents
    <i class="fa fa-recycle text-red" data-toggle="tooltip" title="" data-original-title="Requires Re-Seed"></i></a></li>
      <li class=""><a href="#deadtorrents" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-exclamation-triangle text-red"></i> Dead Torrents
    <i class="fa fa-recycle text-red" data-toggle="tooltip" title="" data-original-title="Requires Re-Seed"></i></a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane fade active in" id="newtorrents">
        <div class="table-responsive">
          <table class="table table-condensed table-striped table-bordered">
            <thead>
              <tr>
                <th class="torrents-icon"></th>
                <th class="torrents-filename">File</th>
                <th>Age</th>
                <th>Size</th>
                <th>S</th>
                <th>L</th>
                <th>C</th>
              </tr>
            </thead>
            <tbody>
              @foreach($torrents as $t)
              <tr class="">
                <td><i class="{{ $t->category->icon }} torrent-icon" data-toggle="tooltip" title="" data-original-title="{{ $t->category->name }} Torrent"></i></td>
                <td>
                  <div class="torrent-file">
                    <div>
                      <a href="{{ route('torrent', array('slug' => $t->slug, 'id' => $t->id)) }}" class="" title="">
    {{ $t->name }}
    </a>
                    </div>
                    <div>
                      <span class="badge-extra">{{ $t->type }}</span>&nbsp;&nbsp;
                      @if($t->stream == "1")<span class="badge-extra"><i class="fa fa-play text-red text-bold" data-toggle="tooltip" title="" data-original-title="Stream Optimized"></i> Stream Optimized</span> @endif
                      @if($t->doubleup == "1")<span class="badge-extra"><i class="fa fa-diamond text-green text-bold" data-toggle="tooltip" title="" data-original-title="Double upload"></i> Double Upload</span> @endif
                      @if($t->free == "1")<span class="badge-extra"><i class="fa fa-star text-gold text-bold" data-toggle="tooltip" title="" data-original-title="100% Free"></i> 100% Free</span> @endif
                      @if(config('other.freeleech') == true)<span class="badge-extra"><i class="fa fa-globe text-blue text-bold" data-toggle="tooltip" title="" data-original-title="Global FreeLeech"></i> Global FreeLeech</span> @endif
                      @if(config('other.doubleup') == true)<span class="badge-extra"><i class="fa fa-globe text-green text-bold" data-toggle="tooltip" title="" data-original-title="Double Upload"></i> Global Double Upload</span> @endif
                      @if($t->leechers >= "5") <span class="badge-extra"><i class="fa fa-fire text-orange text-bold" data-toggle="tooltip" title="" data-original-title="Hot!"></i> Hot!</span> @endif
                      @if($t->sticky == 1) <span class="badge-extra"><i class="fa fa-thumb-tack text-black text-bold" data-toggle="tooltip" title="" data-original-title="Sticky!"></i> Sticky!</span> @endif
                      @if($t->highspeed == 1)<span class="badge-extra"><i class="fa fa-tachometer text-red text-bold" data-toggle="tooltip" title="" data-original-title="High Speeds!"></i> High Speeds!</span> @endif
                    </div>
                  </div>
                </td>
                <td>
                  <span data-toggle="tooltip" title="" data-original-title="">{{ $t->created_at->diffForHumans() }}</span>
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
              <th class="torrents-icon"></th>
              <th class="torrents-filename">File</th>
              <th>Age</th>
              <th>Size</th>
              <th>S</th>
              <th>L</th>
              <th>C</th>
            </thead>
            <tbody>
              @foreach($best as $b)
              <tr class="">
                <td><i class="{{ $b->category->icon }} torrent-icon" data-toggle="tooltip" title="" data-original-title="{{ $b->category->name }} Torrent"></i></td>
                <td>
                  <div class="torrent-file">
                    <div>
                      <a href="{{ route('torrent', array('slug' => $b->slug, 'id' => $b->id)) }}" class="" title="">
      {{ $b->name }}
      </a>
                    </div>
                    <div>
                      <span class="badge-extra">{{ $b->type }}</span>&nbsp;&nbsp;
                      @if($b->stream == "1")<span class="badge-extra"><i class="fa fa-play text-red text-bold" data-toggle="tooltip" title="" data-original-title="Stream Optimized"></i> Stream Optimized</span> @endif
                      @if($b->doubleup == "1")<span class="badge-extra"><i class="fa fa-diamond text-green text-bold" data-toggle="tooltip" title="" data-original-title="Double upload"></i> Double Upload</span> @endif
                      @if($b->free == "1")<span class="badge-extra"><i class="fa fa-star text-gold text-bold" data-toggle="tooltip" title="" data-original-title="100% Free"></i> 100% Free</span> @endif
                      @if(config('other.freeleech') == true)<span class="badge-extra"><i class="fa fa-globe text-blue text-bold" data-toggle="tooltip" title="" data-original-title="Global FreeLeech"></i> Global FreeLeech</span> @endif
                      @if(config('other.doubleup') == true)<span class="badge-extra"><i class="fa fa-globe text-green text-bold" data-toggle="tooltip" title="" data-original-title="Double Upload"></i> Global Double Upload</span> @endif
                      @if($b->leechers >= "5") <span class="badge-extra"><i class="fa fa-fire text-orange text-bold" data-toggle="tooltip" title="" data-original-title="Hot!"></i> Hot!</span> @endif
                      @if($b->sticky == 1) <span class="badge-extra"><i class="fa fa-thumb-tack text-black text-bold" data-toggle="tooltip" title="" data-original-title="Sticky!"></i> Sticky!</span> @endif
                      @if($b->highspeed == 1)<span class="badge-extra"><i class="fa fa-tachometer text-red text-bold" data-toggle="tooltip" title="" data-original-title="High Speeds!"></i> High Speeds!</span> @endif
                    </div>
                  </div>
                </td>
                <td>
                  <span data-toggle="tooltip" title="" data-original-title="">{{ $b->created_at->diffForHumans() }}</span>
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
              <th class="torrents-icon"></th>
              <th class="torrents-filename">File</th>
              <th>Age</th>
              <th>Size</th>
              <th>S</th>
              <th>L</th>
              <th>C</th>
            </thead>
            <tbody>
              @foreach($leeched as $l)
              <tr class="">
                <td><i class="{{ $l->category->icon }} torrent-icon" data-toggle="tooltip" title="" data-original-title="{{ $l->category->name }} Torrent"></i></td>
                <td>
                  <div class="torrent-file">
                    <div>
                      <a href="{{ route('torrent', array('slug' => $l->slug, 'id' => $l->id)) }}" class="" title="">
      {{ $l->name }}
      </a>
                    </div>
                    <div>
                      <span class="badge-extra">{{ $l->type }}</span>&nbsp;&nbsp;
                      @if($l->stream == "1")<span class="badge-extra"><i class="fa fa-play text-red text-bold" data-toggle="tooltip" title="" data-original-title="Stream Optimized"></i> Stream Optimized</span> @endif
                      @if($l->doubleup == "1")<span class="badge-extra"><i class="fa fa-diamond text-green text-bold" data-toggle="tooltip" title="" data-original-title="Double upload"></i> Double Upload</span> @endif
                      @if($l->free == "1")<span class="badge-extra"><i class="fa fa-star text-gold text-bold" data-toggle="tooltip" title="" data-original-title="100% Free"></i> 100% Free</span> @endif
                      @if(config('other.freeleech') == true)<span class="badge-extra"><i class="fa fa-globe text-blue text-bold" data-toggle="tooltip" title="" data-original-title="Global FreeLeech"></i> Global FreeLeech</span> @endif
                      @if(config('other.doubleup') == true)<span class="badge-extra"><i class="fa fa-globe text-green text-bold" data-toggle="tooltip" title="" data-original-title="Double Upload"></i> Global Double Upload</span> @endif
                      @if($l->leechers >= "5") <span class="badge-extra"><i class="fa fa-fire text-orange text-bold" data-toggle="tooltip" title="" data-original-title="Hot!"></i> Hot!</span> @endif
                      @if($l->sticky == 1) <span class="badge-extra"><i class="fa fa-thumb-tack text-black text-bold" data-toggle="tooltip" title="" data-original-title="Sticky!"></i> Sticky!</span> @endif
                      @if($l->highspeed == 1)<span class="badge-extra"><i class="fa fa-tachometer text-red text-bold" data-toggle="tooltip" title="" data-original-title="High Speeds!"></i> High Speeds!</span> @endif
                    </div>
                  </div>
                </td>
                <td>
                  <span data-toggle="tooltip" title="" data-original-title="">{{ $l->created_at->diffForHumans() }}</span>
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
                <th class="torrents-filename">File</th>
                <th>Age</th>
                <th>Size</th>
                <th>S</th>
                <th>L</th>
                <th>C</th>
              </tr>
            </thead>
            <tbody>
              @foreach($dying as $d)
              <tr class="">
                <td><i class="{{ $d->category->icon }} torrent-icon" data-toggle="tooltip" title="" data-original-title="{{ $d->category->name }} Torrent"></i></td>
                <td>
                  <div class="torrent-file">
                    <div>
                      <a href="{{ route('torrent', array('slug' => $d->slug, 'id' => $d->id)) }}" class="" title="">
      {{ $d->name }}
      </a>
                    </div>
                    <div>
                      <span class="badge-extra">{{ $d->type }}</span>&nbsp;&nbsp;
                      @if($d->stream == "1")<span class="badge-extra"><i class="fa fa-play text-red text-bold" data-toggle="tooltip" title="" data-original-title="Stream Optimized"></i> Stream Optimized</span> @endif
                      @if($d->doubleup == "1")<span class="badge-extra"><i class="fa fa-diamond text-green text-bold" data-toggle="tooltip" title="" data-original-title="Double upload"></i> Double Upload</span> @endif
                      @if($d->free == "1")<span class="badge-extra"><i class="fa fa-star text-gold text-bold" data-toggle="tooltip" title="" data-original-title="100% Free"></i> 100% Free</span> @endif
                      @if(config('other.freeleech') == true)<span class="badge-extra"><i class="fa fa-globe text-blue text-bold" data-toggle="tooltip" title="" data-original-title="Global FreeLeech"></i> Global FreeLeech</span> @endif
                      @if(config('other.doubleup') == true)<span class="badge-extra"><i class="fa fa-globe text-green text-bold" data-toggle="tooltip" title="" data-original-title="Double Upload"></i> Global Double Upload</span> @endif
                      @if($d->leechers >= "5") <span class="badge-extra"><i class="fa fa-fire text-orange text-bold" data-toggle="tooltip" title="" data-original-title="Hot!"></i> Hot!</span> @endif
                      @if($d->sticky == 1) <span class="badge-extra"><i class="fa fa-thumb-tack text-black text-bold" data-toggle="tooltip" title="" data-original-title="Sticky!"></i> Sticky!</span> @endif
                      @if($d->highspeed == 1)<span class="badge-extra"><i class="fa fa-tachometer text-red text-bold" data-toggle="tooltip" title="" data-original-title="High Speeds!"></i> High Speeds!</span> @endif
                    </div>
                  </div>
                </td>
                <td>
                  <span data-toggle="tooltip" title="" data-original-title="">{{ $d->created_at->diffForHumans() }}</span>
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
                <th class="torrents-filename">File</th>
                <th>Age</th>
                <th>Size</th>
                <th>S</th>
                <th>L</th>
                <th>C</th>
              </tr>
            </thead>
            <tbody>
              @foreach($dead as $d)
              <tr class="">
                <td><i class="{{ $d->category->icon }} torrent-icon" data-toggle="tooltip" title="" data-original-title="{{ $d->category->name }} Torrent"></i></td>
                <td>
                  <div class="torrent-file">
                    <div>
                      <a href="{{ route('torrent', array('slug' => $d->slug, 'id' => $d->id)) }}" class="" title="">
      {{ $d->name }}
      </a>
                    </div>
                    <div>
                      <span class="badge-extra">{{ $d->type }}</span>&nbsp;&nbsp;
                      @if($d->stream == "1")<span class="badge-extra"><i class="fa fa-play text-red text-bold" data-toggle="tooltip" title="" data-original-title="Stream Optimized"></i> Stream Optimized</span> @endif
                      @if($d->doubleup == "1")<span class="badge-extra"><i class="fa fa-diamond text-green text-bold" data-toggle="tooltip" title="" data-original-title="Double upload"></i> Double Upload</span> @endif
                      @if($d->free == "1")<span class="badge-extra"><i class="fa fa-star text-gold text-bold" data-toggle="tooltip" title="" data-original-title="100% Free"></i> 100% Free</span> @endif
                      @if(config('other.freeleech') == true)<span class="badge-extra"><i class="fa fa-globe text-blue text-bold" data-toggle="tooltip" title="" data-original-title="Global FreeLeech"></i> Global FreeLeech</span> @endif
                      @if(config('other.doubleup') == true)<span class="badge-extra"><i class="fa fa-globe text-green text-bold" data-toggle="tooltip" title="" data-original-title="Double Upload"></i> Global Double Upload</span> @endif
                      @if($d->leechers >= "5") <span class="badge-extra"><i class="fa fa-fire text-orange text-bold" data-toggle="tooltip" title="" data-original-title="Hot!"></i> Hot!</span> @endif
                      @if($d->sticky == 1) <span class="badge-extra"><i class="fa fa-thumb-tack text-black text-bold" data-toggle="tooltip" title="" data-original-title="Sticky!"></i> Sticky!</span> @endif
                      @if($d->highspeed == 1)<span class="badge-extra"><i class="fa fa-tachometer text-red text-bold" data-toggle="tooltip" title="" data-original-title="High Speeds!"></i> High Speeds!</span> @endif
                    </div>
                  </div>
                </td>
                <td>
                  <span data-toggle="tooltip" title="" data-original-title="">{{ $d->created_at->diffForHumans() }}</span>
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

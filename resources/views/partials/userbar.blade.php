<div class="ratio-bar">
  <div class="container-fluid">
    <ul class="list-inline">
      <li><i class="fa fa-user text-black"></i>
        <a href="{{ route('profil', array('username' => Auth::user()->username, 'id' => Auth::user()->id)) }}" class="l-header-user-data-link">
          <span class="badge-user" style="color:{{ Auth::user()->group->color }}"><strong>{{ Auth::user()->username }}</strong>@if(Auth::user()->getWarning() > 0) <i class="fa fa-exclamation-circle text-orange" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="{{ trans('common.active-warrning') }}"></i>@endif</span>
        </a>
      </li>
      <li><i class="fa fa-group text-black"></i>
        <span class="badge-user text-bold" style="color:{{ Auth::user()->group->color }}; background-image:{{ Auth::user()->group->effect }};"><i class="{{ Auth::user()->group->icon }}" data-toggle="tooltip" title="" data-original-title="{{ Auth::user()->group->name }}"></i><strong> {{ Auth::user()->group->name }}</strong></span>
      </li>
      <li><i class="fa fa-arrow-up text-green text-bold"></i> {{ trans('common.upload') }}: {{ Auth::user()->getUploaded() }}</li>
      <li><i class="fa fa-arrow-down text-red text-bold"></i> {{ trans('common.download') }}: {{ Auth::user()->getDownloaded() }}</li>
      <li><i class="fa fa-signal text-blue text-bold"></i> {{ trans('common.ratio') }}: {{ Auth::user()->getRatioString() }}</li>
      <li><i class="fa fa-exchange text-orange text-bold"></i> {{ trans('common.buffer') }}: {{ Auth::user()->untilRatio(config('other.ratio')) }}</li>
      <li><i class="fa fa-upload text-green text-bold"></i>
        <a href="{{ route('myactive', array('username' => Auth::user()->username, 'id' => Auth::user()->id)) }}" title="{{ trans('torrent.my-active-torrents') }}"><span class="text-blue"> {{ trans('torrent.seeding') }}:</span></a> {{ Auth::user()->getSeeding() }}
      </li>
      <li><i class="fa fa-download text-red text-bold"></i>
        <a href="{{ route('myactive', array('username' => Auth::user()->username, 'id' => Auth::user()->id)) }}" title="{{ trans('torrent.my-active-torrents') }}"><span class="text-blue"> {{ trans('torrent.leeching') }}:</span></a> {{ Auth::user()->getLeeching() }}
      </li>
      <li><i class="fa fa-exclamation-circle text-orange text-bold"></i>
        <a href="#" title="{{ trans('torrent.hit-and-runs') }}"><span class="text-blue"> {{ trans('common.warnings') }}:</span></a> {{ Auth::user()->getWarning() }}
      </li>
      <li><i class="fa fa-star text-purple text-bold"></i>
        <a href="{{ route('bonus') }}" title="{{ trans('user.my-bonus-points') }}"><span class="text-blue"> {{ trans('bon.bon') }}:</span></a> {{ Auth::user()->getSeedbonus() }}
      </li>
      <li><i class="fa fa-viacoin text-bold"></i>
        <a href="{{ route('profil', array('username' => Auth::user()->username, 'id' => Auth::user()->id)) }}" title="{{ trans('user.my-fl-tokens') }}"><span class="text-blue"> {{ trans('common.fl_tokens') }}:</span></a> {{ Auth::user()->fl_tokens }}
      </li>
    </ul>
  </div>
</div>

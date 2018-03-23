<div class="ratio-bar">
  <div class="container-fluid">
    <ul class="list-inline">
      <li><i class="fa fa-user text-black"></i>
        <a href="{{ route('profile', array('username' => auth()->user()->username, 'id' => auth()->user()->id)) }}" class="l-header-user-data-link">
          <span class="badge-user" style="color:{{ auth()->user()->group->color }}"><strong>{{ auth()->user()->username }}</strong>@if(auth()->user()->getWarning() > 0) <i class="fa fa-exclamation-circle text-orange" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="{{ trans('common.active-warning') }}"></i>@endif</span>
        </a>
      </li>
      <li><i class="fa fa-group text-black"></i>
        <span class="badge-user text-bold" style="color:{{ auth()->user()->group->color }}; background-image:{{ auth()->user()->group->effect }};"><i class="{{ auth()->user()->group->icon }}" data-toggle="tooltip" title="" data-original-title="{{ auth()->user()->group->name }}"></i><strong> {{ auth()->user()->group->name }}</strong></span>
      </li>
      <li><i class="fa fa-arrow-up text-green text-bold"></i> {{ trans('common.upload') }}: {{ auth()->user()->getUploaded() }}</li>
      <li><i class="fa fa-arrow-down text-red text-bold"></i> {{ trans('common.download') }}: {{ auth()->user()->getDownloaded() }}</li>
      <li><i class="fa fa-signal text-blue text-bold"></i> {{ trans('common.ratio') }}: {{ auth()->user()->getRatioString() }}</li>
      <li><i class="fa fa-exchange text-orange text-bold"></i> {{ trans('common.buffer') }}: {{ auth()->user()->untilRatio(config('other.ratio')) }}</li>
      <li><i class="fa fa-upload text-green text-bold"></i>
        <a href="{{ route('myactive', array('username' => auth()->user()->username, 'id' => auth()->user()->id)) }}" title="{{ trans('torrent.my-active-torrents') }}"><span class="text-blue"> {{ trans('torrent.seeding') }}:</span></a> {{ auth()->user()->getSeeding() }}
      </li>
      <li><i class="fa fa-download text-red text-bold"></i>
        <a href="{{ route('myactive', array('username' => auth()->user()->username, 'id' => auth()->user()->id)) }}" title="{{ trans('torrent.my-active-torrents') }}"><span class="text-blue"> {{ trans('torrent.leeching') }}:</span></a> {{ auth()->user()->getLeeching() }}
      </li>
      <li><i class="fa fa-exclamation-circle text-orange text-bold"></i>
        <a href="#" title="{{ trans('torrent.hit-and-runs') }}"><span class="text-blue"> {{ trans('common.warnings') }}:</span></a> {{ auth()->user()->getWarning() }}
      </li>
      <li><i class="fa fa-star text-purple text-bold"></i>
        <a href="{{ route('bonus') }}" title="{{ trans('user.my-bonus-points') }}"><span class="text-blue"> {{ trans('bon.bon') }}:</span></a> {{ auth()->user()->getSeedbonus() }}
      </li>
      <li><i class="fa fa-viacoin text-bold"></i>
        <a href="{{ route('profile', array('username' => auth()->user()->username, 'id' => auth()->user()->id)) }}" title="{{ trans('user.my-fl-tokens') }}"><span class="text-blue"> {{ trans('common.fl_tokens') }}:</span></a> {{ auth()->user()->fl_tokens }}
      </li>
    </ul>
  </div>
</div>

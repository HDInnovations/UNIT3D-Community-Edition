@extends('layout.default')

@section('title')
<title>{{ $category->name }} - {{ trans('common.category') }} - {{ Config::get('other.title') }}</title>
@stop

@section('meta')
<meta name="description" content="{{ 'Découvrez tout les torrents dans la catégorie ' . $category->name  . ' disponible en téléchargement gratuit' }}">
@stop

@section('breadcrumb')
<li>
    <a href="{{ route('categories') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Categories</span>
    </a>
</li>
<li>
    <a href="{{ route('category', array('slug' => $category->slug, 'id' => $category->id)) }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $category->name }}</span>
    </a>
</li>
@stop

@section('content')
<div class="container box">
  <div class="header gradient green">
    <div class="inner_content">
      <h1>Torrents in {{ $category->name }}</h1>
    </div>
  </div>
<div class="torrents col-md-12">
  <table class="table table-bordered table-striped table-hover">
    <thead>
      <tr>
        <th>Category</th>
        <th>{{ trans('common.name') }}</th>
        <th><i class="fa fa-clock-o"></i></th>
        <th><i class="fa fa-file"></i></th>
        <th><i class="fa fa-check-square-o"></i></th>
        <th><i class="fa fa-arrow-circle-up"></i></th>
        <th><i class="fa fa-arrow-circle-down"></i></th>
      </tr>
    </thead>
    <tbody>
      @foreach($torrents as $k => $t)
      @if($t->sticky == "1")
      <tr class="info">
      @else
      <tr>
      @endif
        <td><a href="{{ route('category', array('slug' => $t->category->slug, 'id' => $t->category->id)) }}">&nbsp;
      <center>
        @if($t->category_id == "1")
        <i class="fa fa-film torrent-icon" data-toggle="tooltip" title="" data-original-title="Movie Torrent"></i>
        @elseif($t->category_id == "2")
        <i class="fa fa-tv torrent-icon" data-toggle="tooltip" title="" data-original-title="TV-Show Torrent"></i>
        @else
        <i class="fa fa-film torrent-icon" data-toggle="tooltip" title="" data-original-title="Movie Torrent"></i>
        @endif
      </center>
      </a></td>
        <td>
          <a class="view-torrent" data-id="{{ $t->id }}" data-slug="{{ $t->slug }}" href="{{ route('torrent', array('slug' => $t->slug, 'id' => $t->id)) }}" data-toggle="tooltip" title="" data-original-title="{{ $t->name }}">{{ $t->name }}</a>
          <a href="{{ route('download', array('slug' => $t->slug, 'id' => $t->id)) }}">&nbsp;&nbsp;
            <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip" title="" data-original-title="DOWNLOAD!"><i class="livicon" data-name="download" data-size="18" data-color="white" data-hc="white" data-l="true"></i></button>
            </a>
              <ul>
                <li>
                  <span class='label label-success'>{{ $t->type }}</span> &nbsp;
                  <strong>
              <span class="badge-extra text-bold text-pink"><i class="fa fa-heart" data-toggle="tooltip" title="" data-original-title="Thanks Given"></i> {{ $t->thanks()->count() }}</span>
              @if($t->stream == "1")<span class="badge-extra text-bold"><i class="fa fa-play text-red" data-toggle="tooltip" title="" data-original-title="Stream Optimized"></i> Stream Optimized</span> @endif
              @if($t->doubleup == "1")<span class="badge-extra text-bold"><i class="fa fa-diamond text-green" data-toggle="tooltip" title="" data-original-title="Double upload"></i> Double Upload</span> @endif
              @if($t->free == "1")<span class="badge-extra text-bold"><i class="fa fa-star text-gold" data-toggle="tooltip" title="" data-original-title="100% Free"></i> 100% Free</span> @endif
              @if(config('other.freeleech') == true)<span class="badge-extra text-bold"><i class="fa fa-globe text-blue" data-toggle="tooltip" title="" data-original-title="Global FreeLeech"></i> Global FreeLeech</span> @endif
              @if(config('other.doubleup') == true)<span class="badge-extra text-bold"><i class="fa fa-globe text-green" data-toggle="tooltip" title="" data-original-title="Double Upload"></i> Global Double Upload</span> @endif
              @if($t->leechers >= "5") <span class="badge-extra text-bold"><i class="fa fa-fire text-orange" data-toggle="tooltip" title="" data-original-title="Hot!"></i> Hot!</span> @endif
              @if($t->sticky == 1) <span class="badge-extra text-bold"><i class="fa fa-thumb-tack text-black" data-toggle="tooltip" title="" data-original-title="Sticky!"></i> Sticky!</span> @endif
              @if($user->updated_at->getTimestamp() < $t->created_at->getTimestamp()) <span class="badge-extra text-bold"><i class="fa fa-magic text-black" data-toggle="tooltip" title="" data-original-title="NEW!"></i> NEW!</span> @endif
              @if($t->highspeed == 1)<span class="badge-extra text-bold"><i class="fa fa-tachometer text-red" data-toggle="tooltip" title="" data-original-title="High Speeds!"></i> High Speeds!</span> @endif
              </strong>
                </li>
              </ul>
        </td>
        <td>
          <time datetime="{{ date('Y-m-d H:m:s', strtotime($t->created_at)) }}">{{$t->created_at->diffForHumans()}}</time>
        </td>
        <td><span class="badge-extra text-blue text-bold">{{ $t->getSize() }}</span></td>
        <td><span class="badge-extra text-orange text-bold">{{ $t->times_completed }} {{ trans('common.times') }}</span></td>
        <td><span class="badge-extra text-green text-bold">{{ $t->seeders }}</span></td>
        <td><span class="badge-extra text-red text-bold">{{ $t->leechers }}</span></td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <!-- <div class="col-sm-4 text-center">
    <div class="text-blue well well-sm">
      <h2><strong>ACTIVITY LEGEND</strong></h2>
      <center>
        <button type="button" class="btn btn-success" data-toggle="tooltip" title="" data-original-title="YOU ARE SEEDING THIS!">
          Seeding
        </button>
        <button type="button" class="btn btn-danger" data-toggle="tooltip" title="" data-original-title="YOU ARE LEECHING THIS!">
          Leeching
        </button>
        <button type="button" class="btn btn-warning" data-toggle="tooltip" title="" data-original-title="YOU HAVE SNATCHED THIS!">
          Snatched
        </button>
      </center>
    </div>
  </div> -->
  {{ $torrents->links() }}
</div>
</div>
</div>
@stop

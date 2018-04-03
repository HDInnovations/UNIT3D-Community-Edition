@extends('layout.default')

@section('title')
	<title>Catalog Torrents - Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('meta')
	<meta name="description" content="Catalog Torrents - Staff Dashboard">
@endsection

@section('breadcrumb')
<li>
  <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
  </a>
</li>
<li class="active">
  <a href="{{ route('getCatalogTorrent') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Catalog Torrents</span>
  </a>
</li>
@endsection

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">Add Torrent To Catalog</div>
        <div class="panel-body">
          <form class="form-horizontal" role="form" method="POST" action="{{ route('postCatalogTorrent') }}">
            {{ csrf_field() }}
            <div class="form-group{{ $errors->has('imdb') ? ' has-error' : '' }}">
              <label for="imdb" class="col-md-4 control-label">Torrent IMDB:</label>
              <div class="col-md-6">
                <input id="imdb" type="text" class="form-control" name="imdb" value="{{ old('imdb') }}" required autofocus>
                @if ($errors->has('imdb'))
                <span class="help-block">
                  <strong>{{ $errors->first('imdb') }}</strong>
                </span>
                @endif
              </div>
            </div>
            <div class="form-group{{ $errors->has('tvdb') ? ' has-error' : '' }}">
              <label for="tvdb" class="col-md-4 control-label">Torrent TVDB:</label>
              <div class="col-md-6">
                <input id="tvdb" type="text" class="form-control" name="tvdb" value="{{ old('tvdb') }}" required autofocus>
                @if ($errors->has('tvdb'))
                <span class="help-block">
                  <strong>{{ $errors->first('tvdb') }}</strong>
                </span>
                @endif
              </div>
            </div>
            <div class="form-group">
              <label for="category_id">Catalog</label>
              <select name="catalog_id" class="form-control">
                @foreach($catalogs as $catalog)
                  <option value="{{ $catalog->id }}">{{ $catalog->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <div class="col-md-8 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                  Add Torrent To Catalog
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container">
  <div class="row">
    <h2>List of Catalogs:</h2>
    <hr>
    <ul class="list-group col-md-8 col-md-offset-2">
      @if(count($catalogs) == 0)
      <p>The are no catalogs in database</p>
      @else
      @foreach($catalogs as $catalog)
      <li class="catalog-list list-group-item"><a href="{{route('getCatalogRecords',['catalog_id'=>$catalog->id])}}" title="Catalog Records">{{$catalog->name}}</a>
        <span class="text-green" style="float:right;"><i class="fa fa-lg fa-list list-icons" aria-hidden="true"></i> {{ $catalog->num_torrent }} Titles</span>
      </li>
      @endforeach
      @endif
    </ul>
  </div>
</div>
@endsection

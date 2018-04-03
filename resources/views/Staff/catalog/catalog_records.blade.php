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
  <a href="{{route('getCatalogRecords',['catalog_id'=>$catalog->id])}}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Catalog Records</span>
  </a>
</li>
@endsection

@section('content')
<div class="container">
<h1 class="title">Records</h1>
<div class="block">
  <div class="table-responsive">
    <table class="table table-condensed table-striped table-bordered">
      <thead>
        <tr>
          <th class="torrents-icon">Year</th>
          <th class="torrents-filename col-sm-6">Name</th>
          <th>IMDB</th>
          <th>TVDB</th>
          <th>Delete</th>
        </tr>
      </thead>
      <tbody>
					@if(count($records) == 0)
		      <p>The are no records in database for the catalog</p>
		      @else
		      @foreach($records as $record)
					<?php $client = new \App\Services\MovieScrapper('aa8b43b8cbce9d1689bef3d0c3087e4d', '3DF2684FC0240D28', 'b8272f7d'); ?>
					<?php $movie = $client->scrape('movie', 'tt'.$record->imdb); ?>
					<tr>
						<td>{{ $movie->releaseYear }}</td>
						<td>{{ $movie->title }}</td>
		        <td><span class="text-green" style="float:right;"><i class="fa fa-lg fa-film list-icons" aria-hidden="true"></i>  #{{$record->imdb}}</span></td>
						<td><span class="text-red" style="float:right;"><i class="fa fa-lg fa-television list-icons" aria-hidden="true"></i>  #{{$record->tvdb}}</span></td>
						<td><i class="fa fa-lg fa-trash list-icons" aria-hidden="true"></i></td>
					</tr>
		      @endforeach
		      @endif
      </tbody>
    </table>
  </div>
</div>
</div>
@endsection

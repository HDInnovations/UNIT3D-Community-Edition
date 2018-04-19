@extends('layout.default')

@section('title')
<title>{{ trans('torrent.torrents') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
<meta name="description" content="{{ 'Torrents ' . config('other.title') }}">
@endsection

@section('breadcrumb')
<li class="active">
  <a href="{{ route('torrents') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.torrents') }}</span>
  </a>
</li>
@endsection

@section('content')
<!-- Search -->
<div class="container box">
  <center>
    <h3 class="filter-title">Current Filters</h3>
    <span id="filter-item-category"></span>
    <span id="filter-item-type"></span>
  </center>
  <hr> {{ Form::open(['action'=>'TorrentController@torrents','method'=>'get','class'=>'form-horizontal form-condensed form-torrent-search form-bordered']) }}
  <div class="form-group">
    <label for="name" class="col-sm-1 label label-default">Name</label>
    <div class="col-sm-9">
      {{ Form::text('search',null,['id'=>'search','placeholder'=>'Name or Title','class'=>'form-control']) }}
    </div>
  </div>
  <div class="form-group">
    <label for="uploader" class="col-sm-1 label label-default">Uploader</label>
    <div class="col-sm-9">
      {{ Form::text('uploader',null,['id'=>'uploader','placeholder'=>'Uploader Username','class'=>'form-control']) }}
    </div>
  </div>
  <div class="form-group">
    <label for="imdb" class="col-sm-1 label label-default">Number</label>
    <div class="col-sm-2">
      {{ Form::text('imdb',null,['id'=>'imdb','placeholder'=>'IMDB #','class'=>'form-control']) }}
    </div>
    <div class="col-sm-2">
      {{ Form::text('tvdb',null,['id'=>'tvdb','placeholder'=>'TVDB #','class'=>'form-control']) }}
    </div>
    <div class="col-sm-2">
      {{ Form::text('tmdb',null,['id'=>'tmdb','placeholder'=>'TMDB #','class'=>'form-control']) }}
    </div>
    <div class="col-sm-2">
      {{ Form::text('mal',null,['id'=>'mal','placeholder'=>'MAL #','class'=>'form-control']) }}
    </div>
  </div>
  <div class="form-group">
    <label for="category" class="col-sm-1 label label-default">Category</label>
    <div class="col-sm-10">
      @foreach($repository->categories() as $id => $category)
      <span class="badge-user">
          {{ Form::checkbox($category,$id,false,['class'=>'category']) }}
          {{ Form::label($category,$category,['class'=>'inline']) }}
      </span>
      @endforeach
    </div>
  </div>
  <div class="form-group">
    <label for="type" class="col-sm-1 label label-default">Type</label>
    <div class="col-sm-10">
      @foreach($repository->types() as $id => $type)
      <span class="badge-user">
          {{ Form::checkbox($type,$type,false,['class'=>'type']) }}
          {{ Form::label($type,$type,['class'=>'inline']) }}
      </span>
      @endforeach
    </div>
  </div>
  <div class="form-group">
    <label for="type" class="col-sm-1 label label-default">Discount</label>
    <div class="col-sm-10">
      <span class="badge-user">
      <label class="inline">
          {{ Form::checkbox('freeleech','1',false,['id'=>'freeleech']) }} <span class="fa fa-star text-gold"></span> 100% Free
      </label>
      </span>
      <span class="badge-user">
      <label class="inline">
          {{ Form::checkbox('doubleupload','1',false,['id'=>'doubleupload']) }}  <span class="fa fa-diamond text-green"></span> Double Upload
      </label>
      </span>
      <span class="badge-user">
      <label class="inline">
          {{ Form::checkbox('featured','1',false,['id'=>'featured']) }}  <span class="fa fa-certificate text-pink"></span> Featured Torrent
      </label>
      </span>
    </div>
    </div>
    <div class="form-group">
      <label for="type" class="col-sm-1 label label-default">Special</label>
      <div class="col-sm-10">
        <span class="badge-user">
        <label class="inline">
            {{ Form::checkbox('stream','1',false,['id'=>'stream']) }}   <span class="fa fa-play text-red"></span> Stream Optimized
        </label>
        </span>
        <span class="badge-user">
        <label class="inline">
            {{ Form::checkbox('highspeed','1',false,['id'=>'highspeed']) }}   <span class="fa fa-tachometer text-red"></span> High Speeds
        </label>
        </span>
        <span class="badge-user">
        <label class="inline">
            {{ Form::checkbox('sd','1',false,['id'=>'sd']) }}   <span class="fa fa-ticket text-orange"></span> SD Content
        </label>
        </span>
      </div>
    </div>
    <div class="form-group">
      <label for="type" class="col-sm-1 label label-default">Health</label>
      <div class="col-sm-10">
        <span class="badge-user">
        <label class="inline">
            {{ Form::checkbox('alive','1',false,['id'=>'alive']) }} <span class="fa fa-smile-o text-green"></span> Alive
        </label>
        </span>
        <span class="badge-user">
        <label class="inline">
            {{ Form::checkbox('dying','1',false,['id'=>'dying']) }}  <span class="fa fa-meh-o text-orange"></span> Dying
        </label>
        </span>
        <span class="badge-user">
        <label class="inline">
            {{ Form::checkbox('dead','0',false,['id'=>'dead']) }}  <span class="fa fa-frown-o text-red"></span> Dead
        </label>
        </span>
      </div>
    </div>
{{ Form::close() }}
<br>
<br>
<div style="float:left;">
  <strong>Extra:</strong>
  <a href="{{ route('categories') }}" class="btn btn-xs btn-primary"><em class="icon fa fa-film"></em> Categories</a>
  <a href="{{ route('catalogs') }}" class="btn btn-xs btn-primary"><em class="icon fa fa-film"></em> Catalogs</a>
</div>
<div style="float:right;">
  <strong>View:</strong>
  <a href="{{ route('torrents') }}" class="btn btn-xs btn-primary"><i class="fa fa-list"></i> List</a>
  <a href="{{ route('poster') }}" class="btn btn-xs btn-primary"><i class="fa fa-image"></i> Poster</a>
  <a href="{{ route('grouping_categories') }}" class="btn btn-xs btn-primary"><i class="fa fa-list"></i> Group</a>
</div>
</div>
<!-- /Search -->

<!-- Results -->
<div class="container-fluid">
    <div class="block" style="padding-bottom:50px;">
        <div style="float:left;">
          <strong>Stats:</strong>
          <span class="label label-primary text-bold"><i class="fa fa-file-o"></i> {{ $torrents->count() }} Torrents</span>
          <span class="label label-success text-bold"><i class="fa fa-smile-o"></i> {{ $alive }} Alive</span>
          <span class="label label-danger text-bold"><i class="fa fa-frown-o"></i> {{ $dead }} Dead</span>
        </div>
        <div style="float:right;">
          <strong>Activity Legend:</strong>
          <button class='btn btn-success btn-circle' type='button' data-toggle='tooltip' title='' data-original-title='Currently Seeding!'><i class='fa fa-arrow-up'></i></button>
          <button class='btn btn-warning btn-circle' type='button' data-toggle='tooltip' title='' data-original-title='Currently Leeching!'><i class='fa fa-arrow-down'></i></button>
          <button class='btn btn-info btn-circle' type='button' data-toggle='tooltip' title='' data-original-title='Started Downloading But Never Completed!'><i class='fa fa-hand-paper-o'></i></button>
          <button class='btn btn-danger btn-circle' type='button' data-toggle='tooltip' title='' data-original-title='You Completed This Download But Are No Longer Seeding It!'><i class='fa fa-thumbs-down'></i></button>
        </div>
      </div>
  <div class="block">
  <center>
     <h1 class="filter-title" id="count"></h1>
  </center>
  <div class="form-horizontal">
    <div class="form-group">
      {{ Form::label('sorting','Sort By:',['class'=>'control-label col-sm-2']) }}
      <div class="col-sm-2">
        {{ Form::select('sorting',$repository->sorting(),'created_at',['class'=>'form-control','id'=>'sorting','placeholder'=>'Select for sorting']) }}
      </div>
      <div class="col-sm-3">
        {{ Form::select('direction',$repository->direction(),'desc',['class'=>'form-control','id'=>'direction']) }}
      </div>
      {{ Form::label('qty','Display:',['class'=>'control-label col-sm-2']) }}
      <div class="col-sm-2">
        {{ Form::select('qty',[25=>25,50=>50,100=>100],25,['class'=>'form-control','id'=>'qty']) }}
      </div>
    </div>
    <hr>
    <div class="torrents col-md-12">
    <div class="table-responsive">
    <table class="table table-condensed table-bordered table-striped table-hover">
      <thead>
        <tr>
          @if($user->show_poster == 1)
          <th>Poster</th>
          @else
          <th> </th>
          @endif
          <th>Category</th>
          <th>{{ trans('common.name') }}</th>
          <th><i class="fa fa-clock-o"></i></th>
          <th><i class="fa fa-file"></i></th>
          <th><i class="fa fa-check-square-o"></i></th>
          <th><i class="fa fa-arrow-circle-up"></i></th>
          <th><i class="fa fa-arrow-circle-down"></i></th>
        </tr>
      </thead>
      <tbody id="result">
      <!-- Result Displayed Here-->
      </tbody>
    </table>
  </div>
  </div>

    <!-- Pagination -->
    <div class="text-center">
      <nav aria-label="Page navigation">
        <ul class="pagination" id="pagination">

        </ul>
      </nav>
    </div>
    <!-- /Pagination -->
  </div>
  <!-- /Results -->
</div>
</div>
@endsection

@section('javascripts')
<script>
 var xhr = new XMLHttpRequest();
        function faceted(page){
            var csrf = "{{ csrf_token() }}";
            var search = $("#search").val();
            var uploader = $("#uploader").val();
            var imdb = $("#imdb").val();
            var tvdb = $("#tvdb").val();
            var tmdb = $("#tmdb").val();
            var mal = $("#mal").val();
            var categories = [];
            var types = [];
            var sorting = $("#sorting").val();
            var direction = $("#direction").val();
            var qty = $("#qty").val();
            var categoryName = [];
            var typeName = [];
            var freeleech = (function() {
            if($("#freeleech").is(":checked")) {
              return $("#freeleech").val();
            }
            })();
            var doubleupload = (function() {
            if($("#doubleupload").is(":checked")) {
              return $("#doubleupload").val();
            }
            })();
            var featured = (function() {
            if($("#featured").is(":checked")) {
              return $("#featured").val();
            }
            })();
            var stream = (function() {
            if($("#stream").is(":checked")) {
              return $("#stream").val();
            }
            })();
            var highspeed = (function() {
            if($("#highspeed").is(":checked")) {
              return $("#highspeed").val();
            }
            })();
            var sd = (function() {
            if($("#sd").is(":checked")) {
              return $("#sd").val();
            }
            })();
            var alive = (function() {
            if($("#alive").is(":checked")) {
              return $("#alive").val();
            }
            })();
            var dying = (function() {
            if($("#dying").is(":checked")) {
              return $("#dying").val();
            }
            })();
            var dead = (function() {
            if($("#dead").is(":checked")) {
              return $("#dead").val();
            }
            })();
            $(".category:checked").each(function(){
                categories.push($(this).val());
                categoryName.push(this.name);
                $("#filter-item-category").html('<label class="label label-default">Category:</label>' +categoryName);
            });
            $(".type:checked").each(function(){
                types.push($(this).val());
                typeName.push(this.name);
                $("#filter-item-type").html('<label class="label label-default">Type:</label>' +typeName);
            });

            if(categories.length == 0){
                $("#filter-item-category").html('')
            }
            if(types.length == 0){
                $("#filter-item-type").html('')
            }

            if(xhr !== 'undefined'){
              xhr.abort();
            }

            xhr = $.ajax({
                url: 'filterTorrents',
                data: {_token:csrf,search:search,uploader:uploader,imdb:imdb,tvdb:tvdb,tmdb:tmdb,mal:mal,categories:categories,types:types,freeleech:freeleech,doubleupload:doubleupload,featured:featured,stream:stream,highspeed:highspeed,sd:sd,alive:alive,dying:dying,dead:dead,sorting:sorting,direction:direction,page:page,qty:qty},
                type: 'get',
                beforeSend:function(){
                    $("#result").html('<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>')
                }
            }).done(function(e){
            if(e['count'] == 0){
                $("#count").html('0 Result(s)');
            } else {
                $("#count").html(e['count'] +' Result(s)');
            }
                $("#result").html(e['result']);
                pagination(e['rows'],e['qty'],e['active']);
            });
        }
    </script>
    <script>
        $(window).on("load",faceted())
    </script>
    <script>
      $("#search").keyup(function(){
        faceted();
      })
    </script>
    <script>
      $("#uploader").keyup(function(){
        faceted();
      })
    </script>
    <script>
      $("#imdb").keyup(function(){
        faceted();
      })
    </script>
    <script>
      $("#tvdb").keyup(function(){
        faceted();
      })
    </script>
    <script>
      $("#tmdb").keyup(function(){
        faceted();
      })
    </script>
    <script>
      $("#mal").keyup(function(){
        faceted();
      })
    </script>
    <script>
        $(".category,.type").on("click",function(){
            faceted();
        });
    </script>
    <script>
        $("#freeleech,#doubleupload,#featured,#stream,#highspeed,#sd,#alive,#dying,#dead").on("click",function(){
            faceted();
        });
    </script>
    <script>
        $("#sorting,#direction,#qty").on('change',function(){
            faceted();
        });
    </script>

    <script>
        function pagination(rows,qty,active){
            //var rows = Object.keys(e).length;
            var q = parseInt(qty);
            if(active == 1){
                var nav = '<li><a aria-label="Previous" style="cursor:not-allowed"><span aria-hidden="true">&laquo;</span></a></li>';
            }else{
                nav = '<li><a onclick="faceted('+(parseInt(active)-1)+')" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
            }
            for(var i=0,j=1;i<=rows;i+=q,j++){
                if((j >= parseInt(active)-6) && (j <= parseInt(active)+8)){
                    nav += '<li class="" id="a'+j+'"><a onclick="faceted('+(j)+')">'+j+'</a>';
                }
            }
            if(active == Math.ceil(rows/qty)){
                nav += '<li><a aria-label="Next" style="cursor:not-allowed"><span aria-hidden="true">&raquo;</span></a></li>';
            }else{
                nav += '<li><a onclick="faceted('+(parseInt(active)+1)+')" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
            }

            $("#pagination").html(nav);

            $("#a"+(parseInt(active))).addClass('active');
        }
    </script>
@endsection

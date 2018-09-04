@extends('layout.default')

@section('title')
    <title> - </title>
@endsection

@section('breadcrumb')
    <li>
        <a href="" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title"></span>
        </a>
    </li>
@endsection

@section('content')
    <!-- Search -->
    <div class="container box">
        <div class="text-center">
            <h3 class="filter-title">Current Filters</h3>
            <span id="filter-item-category"></span>
            <span id="filter-item-type"></span>
        </div>
        <hr>
        
        <div class="form-group">
            <label for="name" class="col-sm-1 label label-default">Name</label>
            <div class="col-sm-9">
                
            </div>
        </div>
        <div class="form-group">
            <label for="imdb" class="col-sm-1 label label-default">Number</label>
            <div class="col-sm-2">
                
            </div>
            <div class="col-sm-2">
                
            </div>
            <div class="col-sm-2">
                
            </div>
            <div class="col-sm-2">
                
            </div>
        </div>
        <div class="form-group">
            <label for="category" class="col-sm-1 label label-default">Category</label>
            <div class="col-sm-10">
                @foreach($repository->categories() as $id => $category)
                    <span class="badge-user">
                        
                        
                    </span>
                @endforeach
            </div>
        </div>
        <div class="form-group">
            <label for="type" class="col-sm-1 label label-default">Type</label>
                <div class="col-sm-10">
                    @foreach($repository->types() as $id => $type)
                        <span class="badge-user">
                            
                            
                        </span>
                    @endforeach
                </div>
            </div>
        
        <hr>
        <div class="form-horizontal">
            <div class="form-group">
                
                <div class="col-sm-2">
                    
                </div>
                <div class="col-sm-3">
                    
                </div>
                
                <div class="col-sm-2">
                    
                </div>
            </div>
        </div>
    </div>
    <!-- /Search -->

    <!-- Results -->
    <div class="container-fluid">
        <div class="block">
            <div class="header gradient silver">
                <div class="inner_content">
                    <h1>
                        <span class="text-red">( !)</span>
                    </h1>
                </div>
            </div>
            <div id="result">
            @include('graveyard.results')
            </div>
        </div>
    </div>
@endsection

@section('javascripts')
    <script>
        var xhr = new XMLHttpRequest();

        function faceted(page) {
          var csrf = "";
          var search = $("#search").val();
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
          $(".category:checked").each(function () {
            categories.push($(this).val());
            categoryName.push(this.name);
            $("#filter-item-category").html('<label class="label label-default">Category:</label>' + categoryName);
          });
          $(".type:checked").each(function () {
            types.push($(this).val());
            typeName.push(this.name);
            $("#filter-item-type").html('<label class="label label-default">Type:</label>' + typeName);
          });
          if (categories.length == 0) {
            $("#filter-item-category").html('')
          }
          if (types.length == 0) {
            $("#filter-item-type").html('')
          }
          if (xhr !== 'undefined') {
            xhr.abort();
          }
          xhr = $.ajax({
            url: 'filterGraveyard',
            data: {
              _token: csrf,
              search: search,
              imdb: imdb,
              tvdb: tvdb,
              tmdb: tmdb,
              mal: mal,
              categories: categories,
              types: types,
              sorting: sorting,
              direction: direction,
              page: page,
              qty: qty
            },
            type: 'get',
            beforeSend: function () {
              $("#result").html('<i class=" fa-spinner fa-spin fa-3x fa-fw"></i>')
            }
          }).done(function (e) {
            $data = $(e);
            $("#result").html($data);
          });
        }
    </script>
    <script>
      $(window).on("load", faceted())
    </script>
    <script>
      $("#search").keyup(function () {
        faceted();
      })
    </script>
    <script>
      $("#imdb").keyup(function () {
        faceted();
      })
    </script>
    <script>
      $("#tvdb").keyup(function () {
        faceted();
      })
    </script>
    <script>
      $("#tmdb").keyup(function () {
        faceted();
      })
    </script>
    <script>
      $("#mal").keyup(function () {
        faceted();
      })
    </script>
    <script>
      $(".category,.type").on("click", function () {
        faceted();
      });
    </script>
    <script>
      $("#sorting,#direction,#qty").on('change', function () {
        faceted();
      });
    </script>
    <script>
      $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var page = url.split('page=')[1];
        window.history.pushState("", "", url);
        faceted(page);
      })
    </script>
    <script>
      $(document).ajaxComplete(function () {
        $('[data-toggle="tooltip"]').tooltip();
      });
    </script>
@endsection
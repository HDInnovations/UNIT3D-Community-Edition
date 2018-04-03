@extends('layout.default')

@section('title')
	<title>Catalogs - Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('meta')
	<meta name="description" content="Catalogs - Staff Dashboard">
@endsection

@section('breadcrumb')
<li>
  <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
  </a>
</li>
<li class="active">
  <a href="{{ route('getCatalog') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Catalog Groups</span>
  </a>
</li>
@endsection

@section('content')
<div class="container">
  <h2>Number of Catalogs:
    @if(count($catalogs) == 0)
      The are no catalogs in database
    @else
      <strong>{{count($catalogs)}}</strong>
    @endif
  </h2>
</div>
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">Add Catalog</div>
        <div class="panel-body">
          <form class="form-horizontal" role="form" method="POST" action="{{ route('postCatalog') }}">
            {{ csrf_field() }}
            <div class="form-group{{ $errors->has('catalog') ? ' has-error' : '' }}">
              <label for="catalog" class="col-md-4 control-label">Catalog Name:</label>
              <div class="col-md-6">
                <input id="catalog" type="text" class="form-control" name="catalog" value="{{ old('catalog') }}" required autofocus>
                @if ($errors->has('catalog'))
                <span class="help-block">
                  <strong>{{ $errors->first('catalog') }}</strong>
                </span>
                @endif
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-8 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                  Add catalog
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
    <ul class="list-group col-md-12">
      @if(count($catalogs) == 0)
      <p>The are no catalogs in database</p>
      @else
      @foreach($catalogs as $catalog)
      <li class="catalog-list list-group-item">{{$catalog->name}}
        <a class="pull-right" href="{{route('deleteCatalog',['catalog_id'=>$catalog->id])}}" title="Delete catalog">
          <i class="fa fa-lg fa-trash list-icons" aria-hidden="true"></i>
        </a>
        <div class="col-md-8 col-md-offset-2 catalog-edit">
          <form action="{{route('editCatalog',['catalog_id'=>$catalog->id])}}" method="post">
            {{csrf_field()}}
            <input type="text" name="catalog" value="{{$catalog->name}}" />
            <input type="submit" class="btn btn-primary btn-xs" value="Edit Name" />
          </form>
        </div>
      </li>
      @endforeach
      @endif
    </ul>
  </div>
</div>
@endsection

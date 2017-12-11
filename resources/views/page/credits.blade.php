@extends('layout.default')

@section('title')
<title>Application Credits - {{ Config::get('other.title') }}</title>
@stop

@section('meta')
<meta name="description" content="Application Credits"> 
@stop

@section('breadcrumb')
<li class="active">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Application Credits</span>
</li>
@stop

@section('content')
<div class="box container">
 <center><h2><strong>Application Credits</strong></h2></center>
<div class="row">
  <div class="col-md-12">
  <div class="col-xs-3 col-sm-2 col-md-1"><i class="devicon-css3-plain-wordmark colored" data-toggle="tooltip" title="CSS3"></i></div>
  <div class="col-xs-3 col-sm-2 col-md-1"><i class="devicon-html5-plain-wordmark colored" data-toggle="tooltip" title="HTML5"></i></div>
  <div class="col-xs-3 col-sm-2 col-md-1"><i class="devicon-javascript-plain colored" data-toggle="tooltip" title="Java Script"></i></div>
  <div class="col-xs-3 col-sm-2 col-md-1"><i class="devicon-php-plain colored" data-toggle="tooltip" title="PHP"></i></div>
  <div class="col-xs-3 col-sm-2 col-md-1"><i class="devicon-backbonejs-plain-wordmark colored" data-toggle="tooltip" title="Backbone JS"></i></div>
  <div class="col-xs-3 col-sm-2 col-md-1"> <i class="devicon-laravel-plain-wordmark colored" data-toggle="tooltip" title="Laravel"></i></div>
  <div class="col-xs-3 col-sm-2 col-md-1"> <i class="devicon-bootstrap-plain-wordmark colored" data-toggle="tooltip" title="Bootstrap"> </i></div>
  </div>
 </div>
</div>  
@stop


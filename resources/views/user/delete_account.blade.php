@extends('layout.default')

@section('title')
<title>Bye! - {{ Config::get('other.title') }}</title>
@stop 

@section('content')
<div class="jumbotron shadowed">
<div class="container">
<h1 class="mt-5 text-center">
<i class="fa fa-times text-danger"></i>Your Account Has Been Deleted From The DataBase!
</h1>
<div class="separator"></div>
<p class="text-center">Sorry To See You Go!</p>
</div>
</div>
@stop
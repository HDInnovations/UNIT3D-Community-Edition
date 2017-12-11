@extends('layout.default')

@section('title')
<title>{{ $user->username }} - Rss Configuration - {{ Config::get('other.title') }}</title>
@stop

@section('breadcrumb')
<li>
    <a href="#" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">RSS Configuration</span>
    </a>
</li>
@stop

@section('content')
<div class="contrainer">


    @foreach($labourTypes as $id => $name)
        <div class="checkbox">
            <label>
                {!! Form::checkbox("labour_types[]", $id) !!} {{$name}}
            </label>
       <div>
    @endforeach



</div>
@stop

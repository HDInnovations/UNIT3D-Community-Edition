@extends('layout.default')

@section('title')
<title>Poll Results - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
<li>
    <a href="{{ route('getPolls') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Polls</span>
    </a>
</li>
<li>
    <a href="{{ route('getPoll', ['slug' => $poll->slug]) }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Poll Results</span>
    </a>
</li>
@endsection

@section('content')
<div class="container">
  <div class="page-title"><h1>Poll Results</h1></div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-chat">
    			<div class="panel-heading">
    		      	<h1 class="panel-title">{{ $poll->title }}</h1>
    		    </div>
    		    <div class="panel-body">
					@foreach ($poll->options as $option)
          <strong>{{ $option->name }}</strong><span class="pull-right">{{ $option->votes }} Vote(s)</span>
          <div class="progress">
          <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="{{ $option->votes }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $option->votes }}%;">
            {{ $option->votes }}
          </div>
          </div>
					@endforeach
    		    </div>
    	  	</div>
        </div>
    </div>
</div>

@endsection

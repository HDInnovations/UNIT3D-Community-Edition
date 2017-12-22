@extends('layout.default')

@section('title')
<title>{{ trans('poll.results') }} - {{ Config::get('other.title') }}</title>
@stop

@section('breadcrumb')
<li>
  <a href="{{ route('polls') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('poll.polls') }}</span>
  </a>
</li>
<li>
  <a href="{{ route('poll', ['slug' => $poll->slug]) }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('poll.poll') }}</span>
  </a>
</li>
<li>
  <a href="{{ route('poll_results', ['slug' => $poll->slug]) }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('poll.results') }}</span>
  </a>
</li>
@stop

@section('content')
<div class="container">
  <div class="page-title">
    <h1>{{ trans('poll.results') }}</h1></div>
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-chat">
        <div class="panel-heading">
          <h1 class="panel-title">{{ $poll->title }}</h1>
        </div>
        <div class="panel-body">
          @foreach ($poll->options as $option)
          <strong>{{ $option->name }}</strong><span class="pull-right">{{ $option->votes }} {{ trans('poll.votes') }}</span>
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

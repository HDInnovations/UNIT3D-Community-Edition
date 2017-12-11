@extends('layout.default')

@section('title')
	<title>Contact - {{ Config::get('other.title') }}</title>
@stop

@section('meta')
	<meta name="description" content="Page de contact de {{ Config::get('other.title') }}. {{ 'Une requête ? Une question ? Contactez nous ici !' }}">
@stop

@section('breadcrumb')
<div class="l-breadcrumb-item" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
    <a href="{{ route('contact') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Contact</span>
    </a>
</div>
@stop

@section('content')
<div class="container box">
<div class="row">
	<div class="col-md-4 box centered-form">
		{{ Form::open(array('route' => 'contact')) }}
		{{ csrf_field() }}
			<div class="form-group">
				<input type="text" name="contact-name" placeholder="Your name" class="form-control">
			</div>

			<div class="form-group">
				<input type="email" name="email" placeholder="E-mail" class="form-control">
			</div>

			<div class="form-group">
				<textarea name="message" placeholder="Message" class="form-control" cols="30" rows="10"></textarea>
			</div>

			<button type="submit" class="btn btn-lg btn-primary btn-block">Send</button>
		{{ Form::close() }}
	</div>

	<div class="col-sm-8">
	    <div class="well well-sm mt-0">
	      <p class="lead text-green text-center"><i class="fa fa-star"></i> <strong>Hello</strong> <i class="fa fa-star"></i></p>
	      <p class="lead text-orange text-center">This contact request will be sent to all staff and one will get back to you as soon as possible.</p>
	    </div>
	</div>
</div>
</div>
@stop

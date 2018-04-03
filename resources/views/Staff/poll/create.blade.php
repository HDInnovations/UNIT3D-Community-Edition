@extends('layout.default')

@section('title')
<title>Polls - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
<li>
    <a href="{{ route('getPolls') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Polls</span>
    </a>
</li>
<li>
    <a href="{{ route('getCreatePoll') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Create Poll</span>
    </a>
</li>
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="page-title"><h1>Make Poll</h1></div>
		<div class="col-md-12">
			<div class="panel panel-chat">
				<div class="panel-heading">Create A Poll</div>

				<div class="panel-body">
					@include('Staff.poll.forms.make')
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

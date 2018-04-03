@extends('layout.default')

@section('breadcrumb')
<li>
    <a href="{{ route('internal') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ config('other.title') }} {{ trans('common.internal') }}</span>
    </a>
</li>
@endsection

@section('content')
<div class="container box">
	<div class="col-md-12 page">
		<div class="header gradient silver">
			<div class="inner_content">
				<div class="page-title"><h1>{{ config('other.title') }} {{ trans('common.internal') }}</h1></div>
			</div>
		</div>
    <div class="row oper-list">
        @foreach($internal as $i)
        <div class="col-xs-6 col-sm-4 col-md-3">
            <div class="text-center oper-item" style="background-color: {{ $i->color }};">
                <a href="{{ route('profile', ['username' => $i->username, 'id' => $i->id]) }}" style="color:#fff;"><h1>{{ $i->username }}</h1></a>
                <span class="badge-user">{{ trans('page.staff-group') }}: {{ $i->name }}</span>
                <br>
                <span class="badge-user">{{ trans('page.staff-title') }}: {{ $i->title }}</span>
                <i class="{{ $i->icon }} oper-icon"></i>
            </div>
        </div>
        @endforeach
	</div>
</div>
</div>
@endsection

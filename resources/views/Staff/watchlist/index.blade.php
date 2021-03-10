@extends('layout.default')

@section('title')
	<title>Watchlist @lang('common.search') - @lang('staff.staff-dashboard') - {{ config('other.title') }}</title>
@endsection

@section('meta')
	<meta name="description" content="Watchlist Search - @lang('staff.staff-dashboard')">
@endsection

@section('breadcrumb')
	<li>
		<a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
			<span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
		</a>
	</li>
	<li>
		<a href="{{ route('staff.watchlist.index') }}" itemprop="url" class="l-breadcrumb-item-link">
			<span itemprop="title" class="l-breadcrumb-item-link-title">Watchlist @lang('common.search')</span>
		</a>
	</li>
@endsection

@section('content')
	<style>
        td {
            vertical-align: middle !important;
        }
	</style>
	<div class="container">
		<div class="block">
			<div class="header gradient silver">
				<div class="inner_content">
					<div class="page-title">
						<h1 style="margin: 0;">Watched Users</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="box container">
		@livewire('watchlist-search')
	</div>
@endsection

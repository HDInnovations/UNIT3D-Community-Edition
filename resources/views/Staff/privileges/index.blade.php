
@extends('layout.default')

@section('title')
    <title>@lang('user.users-roles-privileges') Manager - @lang('staff.staff-dashboard') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('user.users-roles-privileges') Manager - @lang('staff.staff-dashboard')">
@endsection

@section('stylesheets')
    <link href="{{ mix('css/privilege-panel.css') }}" rel="stylesheet">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user_search') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('user.users-roles-privileges')</span>
        </a>
    </li>
@endsection

@section('content')

    @livewire(App\Http\Livewire\Staff\PrivilegePanel::class)

@endsection

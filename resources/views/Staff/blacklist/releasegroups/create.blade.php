@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="#" itemprop="url" class="breadcrumb__link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Blacklists</span>
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.blacklists.releasegroups.index') }}" class="breadcrumb__link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Release Groups</span>
        </a>
    </li>
    <li class="breadcrumb--active">
            {{ __('common.add') }} releasegroup
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>{{ __('common.add') }} Blacklisted Group</h2>
        <div class="table-responsive">
            <form role="form" method="POST" action="{{ route('staff.blacklists.releasegroups.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>{{ __('common.name') }}</th>
                            <th>Reason</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td>
                                <label>
                                    <input type="text" class="form-control" name="name" required>
                                </label>
                            </td>
                            <td>
                                <label>
                                    <input type="text" class="form-control" name="reason">
                                </label>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-default">{{ __('common.submit') }}</button>
            </form>
        </div>
    </div>
@endsection
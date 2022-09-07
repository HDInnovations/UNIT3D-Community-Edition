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
        {{ $releasegroup->name }}
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>{{ $releasegroup->name }}</h2>
        <div class="table-responsive">
            <form role="form" method="POST"
                  action="{{ route('staff.blacklists.releasegroups.update', ['name' => $releasegroup->name, 'id' => $releasegroup->id]) }}">
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
                                    <input type="text" name="name" value="{{ $releasegroup->name }}" class="form-control" required/>
                                </label>
                            </td>
                            <td>
                                <label>
                                    <input type="text" name="reason" value="{{ $releasegroup->reason }}" class="form-control"/>
                                </label>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <button type="submit" class="btn btn-primary">{{ __('common.submit') }}</button>
            </form>
        </div>
    </div>
@endsection
@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.internals.index') }}" class="breadcrumb__link">
            Internals
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.new-adj') }}
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>{{ __('common.add') }} Internal-Group</h2>
        <div class="table-responsive">
            <form role="form" method="POST" action="{{ route('staff.internals.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>{{ __('common.name') }}</th>
                            <th>Icon</th>
                            <th>Effect</th>
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
                                    <input type="text" class="form-control" name="icon" value="fa-magic">
                                </label>
                            </td>

                            <td>
                                <label>
                                    <input type="text" class="form-control" name="effect" value="none">
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

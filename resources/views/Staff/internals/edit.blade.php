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
        {{ __('common.edit') }}
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>{{ $internal->name }} Internal Group</h2>
        <div class="table-responsive">
            <form role="form" method="POST"
                  action="{{ route('staff.internals.update', ['name' => $internal->name, 'id' => $internal->id]) }}">
                @csrf
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Icon</th>
                            <th>Effect</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td>
                                <label>
                                    <input type="text" name="name" value="{{ $internal->name }}" class="form-control"/>
                                </label>
                            </td>

                            <td>
                                <label>
                                    <input type="text" name="icon" value="{{ $internal->icon }}" class="form-control"/>
                                </label>
                            </td>

                            <td>
                                <label>
                                    <input type="text" name="effect" value="{{ $internal->effect }}"
                                           class="form-control"/>
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

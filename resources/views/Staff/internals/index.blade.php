@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        Internals
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Internal Groups</h2>
        <a href="{{ route('staff.internals.create') }}" class="btn btn-primary">Add New Internal Group</a><br><br>
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('common.name') }}</th>
                    <th>Icon</th>
                    <th>Effect</th>
                    <th width="15%">{{ __('common.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($internals as $internal)
                    <tr>
                        <td>
                            {{ $internal->id }}
                        </td>
                        <td>
                            {{ $internal->name }}
                        </td>

                        <td>
                            {{ $internal->icon }}
                        </td>

                        <td>
                            {{ $internal->effect }}
                        </td>

                        <td>
                            <form action="{{ route('staff.internals.destroy', ['id' => $internal->id]) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')
                                <a href="{{ route('staff.internals.edit', ['id' => $internal->id]) }}"
                                   class="btn btn-warning">{{ __('common.edit') }}</a>
                                <button type="submit" class="btn btn-danger">{{ __('common.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

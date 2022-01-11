@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.internals.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Internals</span>
        </a>
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

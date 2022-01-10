@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.internals.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Internals</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.internals.edit', ['name' => $internal->name, 'id' => $internal->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $internal->name }}</span>
        </a>
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

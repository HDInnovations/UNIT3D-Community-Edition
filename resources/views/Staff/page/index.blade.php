@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.pages.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Pages</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Pages</h2>
        <a href="{{ route('staff.pages.create') }}" class="btn btn-primary">Add a new page</a>

        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($page as $p)
                <tr>
                    <td>
                        <a href="{{ route('staff.pages.edit', ['slug' => $p->slug, 'id' => $p->id]) }}">{{ $p->name }}</a>
                    </td>
                    <td>{{ date('d M Y', $p->created_at->getTimestamp()) }}</td>
                    <td>
                        <form action="{{ route('staff.pages.destroy', ['slug' => $p->slug, 'id' => $p->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <a href="{{ route('staff.pages.edit', ['slug' => $p->slug, 'id' => $p->id]) }}"
                               class="btn btn-warning">@lang('common.edit')</a>
                            <button type="submit" class="btn btn-danger">@lang('common.delete')</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    </div>
@endsection

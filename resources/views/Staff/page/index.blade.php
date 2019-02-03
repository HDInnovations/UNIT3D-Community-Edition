@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff_page_index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Pages</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Pages</h2>
        <a href="{{ route('staff_page_add') }}" class="btn btn-primary">Add a new page</a>

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
                        <a href="{{ route('staff_page_edit_form', ['slug' => $p->slug, 'id' => $p->id]) }}">{{ $p->name }}</a>
                    </td>
                    <td>{{ date('d M Y', $p->created_at->getTimestamp()) }}</td>
                    <td>
                        <a href="{{ route('staff_page_edit_form', ['slug' => $p->slug, 'id' => $p->id]) }}"
                           class="btn btn-warning">Edit</a>
                        <a href="{{ route('staff_page_delete', ['slug' => $p->slug, 'id' => $p->id]) }}"
                           class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    </div>
@endsection

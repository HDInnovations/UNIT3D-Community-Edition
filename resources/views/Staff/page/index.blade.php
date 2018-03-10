@extends('layout.default')

@section('content')
<div class="container box">
		<h2>Pages</h2>
		<a href="{{ route('staff_page_add') }}" class="btn btn-primary">Add a new page</a>

		<table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pages as $page)
                    <tr>
  						<td><a href="{{ route('staff_page_edit', ['slug' => $page->slug, 'id' => $page->id]) }}">{{ $page->name }}</a></td>
  						<td>{{ date('d M Y', $page->created_at->getTimestamp()) }}</td>
  						<td><a href="{{ route('staff_page_delete', ['slug' => $page->slug, 'id' => $page->id]) }}" class="btn btn-danger">Delete</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</div>
@endsection

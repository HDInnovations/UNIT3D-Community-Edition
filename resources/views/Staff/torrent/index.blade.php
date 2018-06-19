@extends('layout.default')

@section('title')
    <title>Torrents - Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff_torrent_index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Torrents</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Torrents</h2>
        <form action="{{route('torrent-search')}}" method="any">
            <input type="text" name="name" id="name" size="25" placeholder="Quick Search by Title" class="form-control"
                   style="float:right;">
        </form>
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($torrents as $t)
                <tr>
                    <td>{{ $t->id }}</a>
                    </td>
                    <td><a href="{{ route('edit', ['slug' => $t->slug, 'id' => $t->id]) }}">{{ $t->name }}</a></td>
                    <td><a href="{{ route('edit', ['slug' => $t->slug, 'id' => $t->id]) }}"
                           class="btn btn-warning">Edit</a>
                        <button data-target="#staffdelete-{{ $t->id }}" data-toggle="modal" class="btn btn-danger">
                            Delete
                        </button>
                        <!-- Torrent Delete Modal -->
                        {{-- Torrent Delete Modal --}}
                        <div class="modal fade" id="staffdelete-{{ $t->id }}" tabindex="-1" role="dialog"
                             aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <meta charset="utf-8">
                                    <title>Delete Torrent: {{ $t->name }}</title>
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Delete Torrent: {{ $t->name }}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            {{ Form::open(['route' => ['delete'] , 'method' => 'post']) }}
                                            <input id="type" type="hidden" name="type" value="Torrent">
                                            <input id="id" type="hidden" name="id" value="{{ $t->id }}">
                                            <input id="slug" type="hidden" name="slug" value="{{ $t->slug }}">
                                            <label for="file_name" class="col-sm-2 control-label">Torrent</label>
                                            <div class="col-sm-10">
                                                <label id="title" name="title" type="hidden" value="{{ $t->name }}">
                                                    <p class="form-control-static">{{ $t->name }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="report_reason" class="col-sm-2 control-label">Reason (Sent To
                                                Uploader Via PM)</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" rows="5" name="message" cols="50"
                                                          id="message"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-10 col-sm-offset-2">
                                                <input class="btn btn-danger" type="submit" value="Delete">
                                            </div>
                                            {{ Form::close() }}
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Torrent Delete Modal -->
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $torrents->links() }}
    </div>
@endsection

@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">__('staff.staff-dashboard')</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.wikis.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Wikis</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Wikis</h2>
        <a href="{{ route('staff.wikis.create') }}" class="btn btn-primary">Add a new wiki</a>
    
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>__('common.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($wikis as $wiki)
                        <tr>
                            <td>
                                <a href="{{ route('wikis.show', ['id' => $wiki->id]) }}">
                                    {{ $wiki->name }}
                                </a>
                            </td>
                            <td>
                                {{ $wiki->category->name }}
                            </td>
                            <td>
                                {{ $wiki->created_at }} ({{ $wiki->created_at->diffForHumans() }})
                            </td>
                            <td>
                                <form action="{{ route('staff.wikis.destroy', ['id' => $wiki->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <a href="{{ route('staff.wikis.edit', ['id' => $wiki->id]) }}"
                                        class="btn btn-warning">__('common.edit')</a>
                                    <button type="submit" class="btn btn-danger">__('common.delete')</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">__('staff.staff-dashboard')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.wiki_categories.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">__('staff.torrent-categories')</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.wiki_categories.create') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Add Wiki Category</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Add A Wiki Category</h2>
        <form role="form" method="POST" action="{{ route('staff.wiki_categories.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">__('common.name')</label>
                <label>
                    <input type="text" class="form-control" name="name">
                </label>
            </div>
            <div class="form-group">
                <label for="name">__('common.position')</label>
                <label>
                    <input type="text" class="form-control" name="position">
                </label>
            </div>
            <div class="form-group">
                <label for="name">Icon (FontAwesome)</label>
                <label>
                    <input type="text" class="form-control" name="icon"
                        placeholder="Example: {{ config('other.font-awesome') }} fa-rocket">
                </label>
            </div>
            <br>
            <br>
    
            <button type="submit" class="btn btn-default">__('common.add')</button>
        </form>
    </div>
@endsection

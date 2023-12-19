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
        <a href="{{ route('staff.wiki_categories.edit', ['id' => $category->id]) }}" itemprop="url"
            class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">__('common.edit') Wiki Category</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>__('common.edit') A Wiki Category</h2>
        <form role="form" method="POST" action="{{ route('staff.wiki_categories.update', ['id' => $category->id]) }}">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="name">__('common.name')</label>
                <label>
                    <input type="text" class="form-control" name="name" value="{{ $category->name }}">
                </label>
            </div>
            <div class="form-group">
                <label for="name">__('common.position')</label>
                <label>
                    <input type="text" class="form-control" name="position" value="{{ $category->position }}">
                </label>
            </div>
            <div class="form-group">
                <label for="name">Icon (FontAwesome)</label>
                <label>
                    <input type="text" class="form-control" name="icon" value="{{ $category->icon }}">
                </label>
            </div>
            <br>
            <br>
    
            <button type="submit" class="btn btn-default">__('common.submit')</button>
        </form>
    </div>
@endsection

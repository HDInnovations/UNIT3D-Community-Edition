@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">__('staff.staff-dashboard')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.wikis.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Wikis</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.wikis.create') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Add New Wiki</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Add a new wiki</h2>
        <form role="form" method="POST" action="{{ route('staff.wikis.store') }}">
            @csrf
            <div class="form-group">
                <label for="name">Wiki __('common.name')</label>
                <label>
                    <input type="text" name="name" class="form-control">
                </label>
            </div>

            <div class="form-group">
                <label for="category_id">Wiki Category</label>
                <label>
                    <select name="category_id" class="form-control">
                        <option value="">--Select Category--</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </label>
            </div>
    
            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="content" cols="30" rows="10" class="form-control"></textarea>
            </div>
    
            <button type="submit" class="btn btn-default">Save</button>
        </form>
    </div>
@endsection

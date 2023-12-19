@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">__('staff.staff-dashboard')</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.wiki_categories.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Wiki Categories</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Categories</h2>
        <a href="{{ route('staff.wiki_categories.create') }}" class="btn btn-primary">Add A Wiki Category</a>
    
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>__('common.position')</th>
                        <th>__('common.name')</th>
                        <th>Icon</th>
                        <th>__('common.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>
                                {{ $category->position }}
                            </td>
                            <td>
                                <a
                                    href="{{ route('staff.wiki_categories.edit', ['id' => $category->id]) }}">{{ $category->name }}</a>
                            </td>
                            <td>
                                <i class="{{ $category->icon }}" aria-hidden="true"></i>
                            </td>
                            <td>
                                <form action="{{ route('staff.wiki_categories.destroy', ['id' => $category->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <a href="{{ route('staff.wiki_categories.edit', ['id' => $category->id]) }}"
                                        class="btn btn-warning">
                                        __('common.edit')
                                    </a>
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

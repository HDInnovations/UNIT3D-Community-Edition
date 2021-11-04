@extends('layout.default')

@section('title')
    <title>@lang('common.edit') Forums - @lang('staff.staff-dashboard') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('common.edit') Forums - @lang('staff.staff-dashboard')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.forums.edit', ['id' => $forum->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('common.edit') Forums</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>@lang('common.edit'): {{ $forum->name }}</h2>

        <form role="form" method="POST" action="{{ route('staff.forums.update', ['id' => $forum->id]) }}">
            @csrf
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ $forum->name }}">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" cols="30"
                        rows="10">{{ $forum->description }}</textarea>
            </div>

            <div class="form-group">
                <label for="forum_type">Forum Type</label>
                <select id="forum_type" name="forum_type" class="form-control">
                    @if ($forum->getCategory() == null)
                        <option value="category" selected>Category (Current)</option>
                        <option value="forum">Forum</option>
                    @else
                        <option value="category">Category</option>
                        <option value="forum" selected>Forum (Current)</option>
                    @endif
                </select>
            </div>

            <div class="form-group">
                <label for="parent_id">Parent forum</label>
                <select id="parent_id" name="parent_id" class="form-control">
                    @if ($forum->getCategory() != null)
                        <option value="{{ $forum->parent_id }}" selected>{{ $forum->getCategory()->name }} (Current)</option>
                    @endif
                    @foreach ($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="position">@lang('common.position')</label>
                <input type="text" id="position" name="position" class="form-control" placeholder="The position number"
                       value="{{ $forum->position }}">
            </div>

            <h3>
                Permissions
            </h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Groups</th>
                        <th>View the forum</th>
                        <th>Read topics</th>
                        <th>Start new topic</th>
                        <th>Reply to topics</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groups as $g)
                        <tr>
                            <td>{{ $g->name }}</td>
                            <td>
                                @if ($g->getPermissionsByForum($forum)->show_forum == true)
                                    <input type="checkbox" checked name="permissions[{{ $g->id }}][show_forum]"
                                           value="1">
                                @else
                                    <input type="checkbox" name="permissions[{{ $g->id }}][show_forum]"
                                           value="1">
                                @endif
                            </td>
                            <td>
                                @if ($g->getPermissionsByForum($forum)->read_topic == true)
                                    <input type="checkbox" checked name="permissions[{{ $g->id }}][read_topic]"
                                           value="1">
                                @else
                                    <input type="checkbox" name="permissions[{{ $g->id }}][read_topic]"
                                           value="1">
                                @endif
                            </td>
                            <td>
                                @if ($g->getPermissionsByForum($forum)->start_topic == true)
                                    <input type="checkbox" checked name="permissions[{{ $g->id }}][start_topic]"
                                           value="1">
                                @else
                                    <input type="checkbox" name="permissions[{{ $g->id }}][start_topic]"
                                           value="1">
                                @endif
                            </td>
                            <td>
                                @if ($g->getPermissionsByForum($forum)->reply_topic == true)
                                    <input type="checkbox" checked name="permissions[{{ $g->id }}][reply_topic]"
                                           value="1">
                                @else
                                    <input type="checkbox" name="permissions[{{ $g->id }}][reply_topic]"
                                           value="1">
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-default">Save Forum</button>
        </form>
    </div>
@endsection

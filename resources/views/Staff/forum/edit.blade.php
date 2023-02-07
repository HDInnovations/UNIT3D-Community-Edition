@extends('layout.default')

@section('title')
    <title>{{ __('common.edit') }} Forums - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('common.edit') }} Forums - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.forums.index') }}" class="breadcrumb__link">
            {{ __('staff.forums') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        {{ $forum->name }}
    </li>
    <li class="breadcrumb--active">
        {{ __('common.edit') }}
    </li>
@endsection

@section('page', 'page__forums-admin--edit')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.edit') }} {{ __('forum.forum') }}</h2>
        <div class="panel__body">
            <form class="form" method="POST" action="{{ route('staff.forums.update', ['id' => $forum->id]) }}">
                @csrf
                <p class="form__group">
                    <input id="name" class="form__text" type="text" name="name" value="{{ $forum->name }}">
                    <label class="form__label form__label--floating" for="name">Title</label>
                </p>
                <p class="form__group">
                    <textarea
                        id="description"
                        name="description"
                        class="form__textarea"
                    >{{ $forum->description }}</textarea>
                    <label class="form__label form__label--floating" for="description">Description</label>
                </p>
                <p class="form__group">
                    <select name="forum_type" class="form__select">
                        @if ($forum->getCategory() == null)
                            <option value="category" selected>Category (Current)</option>
                            <option value="forum">Forum</option>
                        @else
                            <option value="category">Category</option>
                            <option value="forum" selected>Forum (Current)</option>
                        @endif
                    </select>
                    <label class="form__label form__label--floating" for="forum_type">Forum Type</label>
                </p>
                <p class="form__group">
                    <select name="parent_id" class="form__select">
                        @if ($forum->getCategory() != null)
                            <option value="{{ $forum->parent_id }}" selected>
                                {{ $forum->getCategory()->name }} (Current)
                            </option>
                        @endif
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <label class="form__label form__label--floating" for="parent_id">Parent forum</label>
                </p>
                <p class="form__group">
                    <input
                        id="position"
                        class="form__text"
                        inputmode="numeric"
                        name="position"
                        pattern="[0-9]*"
                        placeholder=""
                        type="text"
                        value="{{ $forum->position }}"
                        required
                    >
                    <label class="form__label form__label--floating" for="position">
                        {{ __('common.position') }}
                    </label>
                </p>
                <p class="form__group">
                    <label class="form__label">Permissions</label>
                    <div class="data-table-wrapper">
                        <table class="data-table">
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
                            @foreach ($groups as $group)
                                <tr>
                                    <td>{{ $group->name }}</td>
                                    <td>
                                        <input
                                            type="checkbox"
                                            name="permissions[{{ $group->id }}][show_forum]"
                                            value="1"
                                            @checked($group->getPermissionsByForum($forum)->show_forum)
                                        />
                                    </td>
                                    <td>
                                        <input
                                            type="checkbox"
                                            name="permissions[{{ $group->id }}][read_topic]"
                                            value="1"
                                            @checked($group->getPermissionsByForum($forum)->read_topic)
                                        />
                                    </td>
                                    <td>
                                        <input
                                            type="checkbox"
                                            name="permissions[{{ $group->id }}][start_topic]"
                                            value="1"
                                            @checked($group->getPermissionsByForum($forum)->start_topic)
                                        />
                                    </td>
                                    <td>
                                        <input
                                            type="checkbox"
                                            name="permissions[{{ $group->id }}][reply_topic]"
                                            value="1"
                                            @checked($group->getPermissionsByForum($forum)->reply_topic)
                                        />
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </p>
                <p class="form__group">
                    <button class="form__button form__button--filled">
                        Save Forum
                    </button>
                </p>
            </form>
        </div>
    </section>
@endsection

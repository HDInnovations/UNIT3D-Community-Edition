@extends('layout.default')

@section('title')
    <title>Moderation - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.moderation.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.torrent-moderation') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered">
                <thead>
                <tr>
                    <th>{{ __('torrent.uploaded') }}</th>
                    <th>{{ __('common.name') }}</th>
                    <th>{{ __('common.category') }}</th>
                    <th>{{ __('common.type') }}</th>
                    <th>{{ __('common.resolution') }}</th>
                    <th>{{ __('torrent.size') }}</th>
                    <th>{{ __('torrent.uploader') }}</th>
                    <th>{{ __('common.moderation-approve') }}</th>
                    <th>{{ __('common.moderation-postpone') }}</th>
                    <th>{{ __('common.moderation-reject') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($pending as $p)
                    <tr>
                        <td><span class="text-red text-bold">{{ $p->created_at->diffForHumans() }}</span></td>
                        <td><a href="{{ route('torrent', ['id' => $p->id]) }}" itemprop="url"
                               class="l-breadcrumb-item-link"><span itemprop="title"
                                                                    class="l-breadcrumb-item-link-title">{{ $p->name }}</span></a>
                        </td>
                        <td><i class="{{ $p->category->icon }} torrent-icon" data-toggle="tooltip"
                               data-original-title="{{ $p->category->name }} Torrent"></i></td>
                        <td>{{ $p->type->name }}</td>
                        <td>{{ $p->resolution->name ?? 'No Res' }}</td>
                        <td>{{ $p->getSize() }}</td>
                        <td><a href="{{ route('users.show', ['username' => $p->user->username]) }}"
                               itemprop="url" class="l-breadcrumb-item-link"><span itemprop="title"
                                                                                   class="l-breadcrumb-item-link-title">{{ $p->user->username }}
                                    ({{ $p->user->group->name }})</span></a></td>
                        <td>
                            <form role="form" method="POST"
                                  action="{{ route('staff.moderation.approve', ['id' => $p->id]) }}"
                                  style="display: inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-labeled btn-success">
                                    <span class="btn-label">
                                        <i class="{{ config('other.font-awesome') }} fa-thumbs-up"></i>
                                    </span>
                                    {{ __('common.moderation-approve') }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <button data-target="#pendpostpone-{{ $p->id }}" data-toggle="modal"
                                    class="btn btn-labeled btn-danger">
                                <span class="btn-label"><i
                                            class="{{ config('other.font-awesome') }} fa-pause"></i></span>
                                {{ __('common.moderation-postpone') }}
                            </button>

                            <div class="modal fade" id="pendpostpone-{{ $p->id }}" tabindex="-1" role="dialog"
                                 aria-hidden="true">
                                <form method="POST" action="{{ route('staff.moderation.postpone') }}">
                                    @csrf
                                    <div class="modal-dialog{{ modal_style() }}">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="{{ __('common.close') }}"><span
                                                            aria-hidden="true">×</span>
                                                </button>
                                                <h4 class="modal-title" id="myModalLabel">
                                                    {{ __('common.moderation-postpone') }} {{ __('torrent.torrent') }}
                                                    : {{ $p->name }}
                                                </h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <input id="type" name="type" type="hidden"
                                                           value="{{ __('torrent.torrent') }}">
                                                    <input id="id" name="id" type="hidden" value="{{ $p->id }}">
                                                    <input id="slug" name="slug" type="hidden" value="{{ $p->slug }}">
                                                    <label for="postpone_reason"
                                                           class="col-sm-2 control-label">{{ __('common.reason') }}</label>
                                                    <div class="col-sm-10">
                                                        <label for="message"></label>
                                                        <textarea title="Postpone message" class="form-control" rows="5"
                                                                  name="message" cols="50" id="message"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-10 col-sm-offset-2">
                                                        <button class="btn btn-danger" type="submit">
                                                            {{ __('common.moderation-postpone') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-sm btn-primary"
                                                        data-dismiss="modal">{{ __('common.close') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </td>
                        <td>
                            <button data-target="#pendreject-{{ $p->id }}" data-toggle="modal"
                                    class="btn btn-labeled btn-danger">
                                <span class="btn-label">
                                    <i class="{{ config('other.font-awesome') }} fa-thumbs-down"></i>
                                </span>
                                {{ __('common.moderation-reject') }}
                            </button>
                            <div class="modal fade" id="pendreject-{{ $p->id }}" tabindex="-1" role="dialog"
                                 aria-hidden="true">
                                <form method="POST" action="{{ route("staff.moderation.reject") }}">
                                    @csrf
                                    <div class="modal-dialog{{ modal_style() }}">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="{{ __('common.close') }}"><span
                                                            aria-hidden="true">×</span>
                                                </button>
                                                <h4 class="modal-title" id="myModalLabel">
                                                    {{ __('common.moderation-reject') }} {{ __('torrent.torrent') }}
                                                    : {{ $p->name }}
                                                </h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <input id="type" type="hidden" name="type"
                                                           value="{{ __('torrent.torrent') }}">
                                                    <input id="id" type="hidden" name="id" value="{{ $p->id }}">
                                                    <input id="slug" type="hidden" name="slug" value="{{ $p->slug }}">
                                                    <label for="file_name"
                                                           class="col-sm-2 control-label">{{ __('torrent.torrent') }}</label>
                                                    <div class="col-sm-10">
                                                        <p id="title" name="title"
                                                           class="form-control-static">{{ $p->name }}</p>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="report_reason"
                                                           class="col-sm-2 control-label">{{ __('common.reason') }}</label>
                                                    <div class="col-sm-10">
                                                        <label for="message"></label>
                                                        <textarea title="Rejection Message" class="form-control"
                                                                  rows="5" name="message" cols="50"
                                                                  id="message"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-10 col-sm-offset-2">
                                                        <button class="btn btn-danger" type="submit">
                                                            {{ __('common.moderation-reject') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-sm btn-primary"
                                                        data-dismiss="modal">{{ __('common.close') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center"><h1>{{ __('torrent.postponed-torrents') }}</h1></div>
    <div class="container box">
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered">
                <thead>
                <tr>
                    <th>{{ __('staff.moderation-since') }}</th>
                    <th>{{ __('common.name') }}</th>
                    <th>{{ __('common.category') }}</th>
                    <th>{{ __('common.type') }}</th>
                    <th>{{ __('common.resolution') }}</th>
                    <th>{{ __('torrent.size') }}</th>
                    <th>{{ __('torrent.uploader') }}</th>
                    <th>{{ __('common.staff') }}</th>
                    <th>{{ __('common.moderation-approve') }}</th>
                    <th>{{ __('common.edit') }}</th>
                    <th>{{ __('common.delete') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($postponed as $post)
                    <tr>
                        <td><span class="text-red text-bold">{{ $post->moderated_at->diffForHumans() }}</span></td>
                        <td><a href="{{ route('torrent', ['id' => $post->id]) }}" itemprop="url"
                               class="l-breadcrumb-item-link"><span itemprop="title"
                                                                    class="l-breadcrumb-item-link-title">{{ $post->name }}</span></a>
                        </td>
                        <td><i class="{{ $post->category->icon }} torrent-icon" data-toggle="tooltip"
                               data-original-title="{{ $post->category->name }} Torrent"></i></td>
                        <td>{{ $post->type->name }}</td>
                        <td>{{ $post->resolution->name ?? 'No Res' }}</td>
                        <td>{{ $post->getSize() }}</td>
                        <td>
                            <a href="{{ route('users.show', ['username' => $post->user->username]) }}"
                               itemprop="url" class="l-breadcrumb-item-link"><span itemprop="title"
                                                                                   class="l-breadcrumb-item-link-title">{{ $post->user->username }}
                                    ({{ $post->user->group->name }})</span></a></td>
                        <td>
                            <a href="{{ route('users.show', ['username' => $post->moderated->username]) }}"
                               itemprop="url" class="l-breadcrumb-item-link"><span itemprop="title"
                                                                                   class="l-breadcrumb-item-link-title">{{ $post->moderated->username }}
                                    ({{ $post->moderated->group->name }})</span></a></td>
                        <td>
                            <form role="form" method="POST"
                                  action="{{ route('staff.moderation.approve', ['id' => $post->id]) }}"
                                  style="display: inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-labeled btn-success">
                                    <span class="btn-label">
                                        <i class="{{ config('other.font-awesome') }} fa-thumbs-up"></i>
                                    </span>
                                    {{ __('common.moderation-approve') }}
                                </button>
                            </form>
                        </td>
                        <td><a href="{{ route('edit', ['id' => $post->id]) }}" role='button'
                               class='btn btn-labeled btn-info'><span class="btn-label"><i
                                            class="{{ config('other.font-awesome') }} fa-pencil"></i></span>{{ __('common.edit') }}
                            </a></td>
                        <td>
                            <button data-target="#postdelete-{{ $post->id }}" data-toggle="modal"
                                    class="btn btn-labeled btn-danger"><span class="btn-label"><i
                                            class="{{ config('other.font-awesome') }} fa-thumbs-down"></i></span>{{ __('common.delete') }}
                            </button>

                            <div class="modal fade" id="postdelete-{{ $post->id }}" tabindex="-1" role="dialog"
                                 aria-hidden="true">
                                <form method="POST" action="{{ route('delete') }}">
                                    @csrf
                                    <div class="modal-dialog{{ modal_style() }}">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="{{ __('common.close') }}"><span
                                                            aria-hidden="true">×</span>
                                                </button>
                                                <h4 class="modal-title" id="myModalLabel">
                                                    {{ __('common.delete') }} {{ __('torrent.torrent') }}: {{ $post->name }}
                                                </h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <input id="type" type="hidden" name="type"
                                                           value="{{ __('torrent.torrent') }}">
                                                    <input id="id" type="hidden" name="id" value="{{ $post->id }}">
                                                    <input id="slug" type="hidden" name="slug"
                                                           value="{{ $post->slug }}">
                                                    <label for="file_name"
                                                           class="col-sm-2 control-label">{{ __('torrent.torrent') }}</label>
                                                    <div class="col-sm-10">
                                                        <p id="title" name="title"
                                                           class="form-control-static">{{ $post->name }}</p>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="report_reason"
                                                           class="col-sm-2 control-label">{{ __('common.reason') }}</label>
                                                    <div class="col-sm-10">
                                                        <label for="message"></label>
                                                        <textarea title="Deletion message" class="form-control" rows="5"
                                                                  name="message" cols="50" id="message"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-10 col-sm-offset-2">
                                                        <button class="btn btn-danger"
                                                                type="submit">{{ __('common.delete') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-sm btn-primary"
                                                        data-dismiss="modal">{{ __('common.close') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center"><h1>{{ __('torrent.rejected') }}</h1></div>
    <div class="container box">
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered">
                <thead>
                <tr>
                    <th>{{ __('staff.moderation-since') }}</th>
                    <th>{{ __('common.name') }}</th>
                    <th>{{ __('common.category') }}</th>
                    <th>{{ __('common.type') }}</th>
                    <th>{{ __('common.resolution') }}</th>
                    <th>{{ __('torrent.size') }}</th>
                    <th>{{ __('torrent.uploader') }}</th>
                    <th>{{ __('common.staff') }}</th>
                    <th>{{ __('common.moderation-approve') }}</th>
                    <th>{{ __('common.moderation-postpone') }}</th>
                    <th>{{ __('common.edit') }}</th>
                    <th>{{ __('common.delete') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($rejected as $reject)
                    <tr>
                        <td><span class="text-red text-red">{{ $reject->moderated_at->diffForHumans() }}</span></td>
                        <td><a href="{{ route('torrent', ['id' => $reject->id]) }}"
                               itemprop="url" class="l-breadcrumb-item-link"><span itemprop="title"
                                                                                   class="l-breadcrumb-item-link-title">{{ $reject->name }}</span></a>
                        </td>
                        <td><i class="{{ $reject->category->icon }} torrent-icon" data-toggle="tooltip"
                               data-original-title="{{ $reject->category->name }} Torrent"></i></td>
                        <td>{{ $reject->type->name }}</td>
                        <td>{{ $reject->resolution->name ?? 'No Res' }}</td>
                        <td>{{ $reject->getSize() }}</td>
                        <td>@if ($reject->user) <a
                                    href="{{ route('users.show', ['username' => $reject->user->username]) }}"
                                    itemprop="url" class="l-breadcrumb-item-link"><span itemprop="title"
                                                                                        class="l-breadcrumb-item-link-title">{{ $reject->user->username }}
                                    ({{ $reject->user->group->name }})</span></a> @else System @endif </td>
                        <td>
                            <a href="{{ route('users.show', ['username' => $reject->moderated->username]) }}"
                               itemprop="url" class="l-breadcrumb-item-link"><span itemprop="title"
                                                                                   class="l-breadcrumb-item-link-title">{{ $reject->moderated->username }}
                                    ({{ $reject->moderated->group->name }})</span></a></td>
                        <td>
                            <form role="form" method="POST"
                                  action="{{ route('staff.moderation.approve', ['id' => $reject->id]) }}"
                                  style="display: inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-labeled btn-success">
                                    <span class="btn-label">
                                        <i class="{{ config('other.font-awesome') }} fa-thumbs-up"></i>
                                    </span>
                                    {{ __('common.moderation-approve') }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <button data-target="#rejectpost-{{ $reject->id }}" data-toggle="modal"
                                    class="btn btn-labeled btn-danger">
                                <span class="btn-label"><i
                                            class="{{ config('other.font-awesome') }} fa-pause"></i></span>
                                {{ __('common.moderation-postpone') }}
                            </button>

                            <div class="modal fade" id="rejectpost-{{ $reject->id }}" tabindex="-1" role="dialog"
                                 aria-hidden="true">
                                <form method="POST" action="{{ route('staff.moderation.postpone') }}">
                                    @csrf
                                    <div class="modal-dialog{{ modal_style() }}">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="{{ __('common.close') }}"><span
                                                            aria-hidden="true">×</span>
                                                </button>
                                                <h4 class="modal-title" id="myModalLabel">
                                                    {{ __('common.moderation-postpone') }} {{ __('torrent.torrent') }}
                                                    : {{ $reject->name }}
                                                </h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <input id="type" name="type" type="hidden"
                                                           value="{{ __('torrent.torrent') }}">
                                                    <input id="id" name="id" type="hidden" value="{{ $reject->id }}">
                                                    <input id="slug" name="slug" type="hidden"
                                                           value="{{ $reject->slug }}">
                                                    <label for="postpone_reason"
                                                           class="col-sm-2 control-label">{{ __('common.reason') }}</label>
                                                    <div class="col-sm-10">
                                                        <label for="message"></label>
                                                        <textarea title="Postpone message" class="form-control" rows="5"
                                                                  name="message" cols="50" id="message"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-10 col-sm-offset-2">
                                                        <button class="btn btn-danger" type="submit">
                                                            {{ __('common.moderation-postpone') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-sm btn-primary" type="button"
                                                        data-dismiss="modal">{{ __('common.close') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </td>
                        <td><a href="{{ route('edit', ['id' => $reject->id]) }}" role='button'
                               class='btn btn-labeled btn-info'><span class="btn-label"><i
                                            class="{{ config('other.font-awesome') }} fa-pencil"></i></span>{{ __('common.edit') }}
                            </a></td>
                        <td>
                            <button data-target="#rejectdelete-{{ $reject->id }}" data-toggle="modal"
                                    class="btn btn-labeled btn-danger"><span class="btn-label"><i
                                            class="{{ config('other.font-awesome') }} fa-thumbs-down"></i></span>{{ __('common.delete') }}
                            </button>

                            <div class="modal fade" id="rejectdelete-{{ $reject->id }}" tabindex="-1" role="dialog"
                                 aria-hidden="true">
                                <form method="POST" action=" {{ route('delete') }}">
                                    @csrf
                                    <div class="modal-dialog{{ modal_style() }}">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="{{ __('common.close') }}"><span
                                                            aria-hidden="true">×</span>
                                                </button>
                                                <h4 class="modal-title" id="myModalLabel">
                                                    {{ __('common.delete') }} {{ __('torrent.torrent') }}: {{ $reject->name }}
                                                </h4>
                                            </div>
                                            <div class="modal-body">

                                                <div class="form-group">
                                                    <input id="type" type="hidden" name="type"
                                                           value="{{ __('torrent.torrent') }}">
                                                    <input id="id" type="hidden" name="id" value="{{ $reject->id }}">
                                                    <input id="slug" type="hidden" name="slug"
                                                           value="{{ $reject->slug }}">
                                                    <label for="file_name"
                                                           class="col-sm-2 control-label">{{ __('torrent.torrent') }}</label>
                                                    <div class="col-sm-10">
                                                        <p id="title" name="title"
                                                           class="form-control-static">{{ $reject->name }}</p>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="report_reason"
                                                           class="col-sm-2 control-label">{{ __('common.reason') }}</label>
                                                    <div class="col-sm-10">
                                                        <label for="message"></label>
                                                        <textarea title="Deletion message" class="form-control" rows="5"
                                                                  name="message" cols="50" id="message"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-10 col-sm-offset-2">
                                                        <button class="btn btn-danger"
                                                                type="submit">{{ __('common.delete') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-sm btn-primary" type="button"
                                                        data-dismiss="modal">{{ __('common.close') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

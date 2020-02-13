@extends('layout.default')

@section('title')
    <title>@lang('common.user') Notes - @lang('staff.staff-dashboard') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="User Notes - @lang('staff.staff-dashboard')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.notes.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.user-notes')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <h2>@lang('staff.user-notes')</h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <h2>@lang('user.note') <span class="text-blue"><strong><i class="{{ config('other.font-awesome') }} fa-note"></i>
                                {{ $notes->count() }} </strong></span>
                    </h2>
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>@lang('common.user')</th>
                                    <th>@lang('common.staff')</th>
                                    <th>@lang('common.message')</th>
                                    <th>@lang('user.created-on')</th>
                                    <th>@lang('common.delete')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($notes) == 0)
                                    <p>The are no notes in database for this user!</p>
                                @else
                                    @foreach ($notes as $note)
                                        <tr>
                                            <td>
                                                <a class="name"
                                                    href="{{ route('users.show', ['username' => $note->noteduser->username]) }}">{{ $note->noteduser->username }}</a>
                                            </td>
                                            <td>
                                                <a class="name"
                                                    href="{{ route('users.show', ['username' => $note->staffuser->username]) }}">{{ $note->staffuser->username }}</a>
                                            </td>
                                            <td>
                                                {{ $note->message }}
                                            </td>
                                            <td>
                                                {{ $note->created_at->toDayDateTimeString() }}
                                                ({{ $note->created_at->diffForHumans() }})
                                            </td>
                                            <td>
                                                <form action="{{ route('staff.notes.destroy', ['id' => $note->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-xs btn-danger"><i
                                                            class="{{ config('other.font-awesome') }} fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="text-center">
                {{ $notes->links() }}
            </div>
        </div>
    </div>
@endsection

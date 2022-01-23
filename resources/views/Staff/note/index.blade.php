@extends('layout.default')

@section('title')
    <title>{{ __('common.user') }} Notes - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="User Notes - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.notes.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.user-notes') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <h2>{{ __('staff.user-notes') }}</h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <h2>{{ __('user.note') }} <span class="text-blue"><strong><i
                                        class="{{ config('other.font-awesome') }} fa-note"></i>
                                {{ $notes->count() }} </strong></span>
                    </h2>
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>{{ __('common.user') }}</th>
                                <th>{{ __('common.staff') }}</th>
                                <th>{{ __('common.message') }}</th>
                                <th>{{ __('user.created-on') }}</th>
                                <th>{{ __('common.delete') }}</th>
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
                                                            class="{{ config('other.font-awesome') }} fa-trash"></i>
                                                </button>
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

@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('outbox') }}" class="breadcrumb__link">
            {{ __('pm.messages') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('pm.outbox') }}
    </li>
@endsection

@section('nav-tabs')
    @include('partials.pmmenu')
@endsection

@section('content')
    <div class="container">
        <div>
            <div>
                <div class="block">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="input-group pull-right">
                                <form role="form" method="POST" action="{{ route('searchPMOutbox') }}">
                                    @csrf
                                    <label for="subject"></label><input type="text" name="subject" id="subject"
                                                                        class="form-control"
                                                                        placeholder="{{ __('pm.search') }}">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-condensed table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <td class="col-sm-2">{{ __('pm.to') }}</td>
                                <td class="col-sm-6">{{ __('pm.subject') }}</td>
                                <td class="col-sm-2">{{ __('pm.sent-at') }}</td>
                                <td class="col-sm-2">{{ __('pm.delete') }}</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($pms as $p)
                                <tr>
                                    <td class="col-sm-2"><a
                                                href="{{ route('users.show', ['username' => $p->receiver->username]) }}">{{ $p->receiver->username }}</a>
                                    </td>
                                    <td class="col-sm-5"><a
                                                href="{{ route('message', ['id' => $p->id]) }}">{{ $p->subject }}</a>
                                    </td>
                                    <td class="col-sm-2">{{ $p->created_at->diffForHumans() }}</td>
                                    <td class="col-sm-2">
                                        <form role="form" method="POST"
                                              action="{{ route('delete-pm', ['id' => $p->id]) }}">
                                            @csrf
                                            <input type="hidden" name="dest" value="outbox"/>
                                            <div class="col-sm-1">
                                                <button type="submit" class="btn btn-xs btn-danger"
                                                        title="{{ __('pm.delete') }}"><i
                                                            class="{{ config('other.font-awesome') }} fa-trash"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="align-center">{{ $pms->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection

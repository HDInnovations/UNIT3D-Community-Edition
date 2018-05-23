@extends('layout.default')

@section('breadcrumb')
    <li class="active">
        <a href="{{ route('outbox') }}">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                {{ trans('pm.outbox') }}
            </span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="header gradient silver">
            <div class="inner_content">
                <h1>{{ trans('pm.private') }} {{ trans('pm.messages') }} - {{ trans('pm.outbox') }}</h1>
            </div>
        </div>
        <div class="row">
            @include('partials.pmmenu')
            <div class="col-md-10">
                <div class="block">
                    <div class="table-responsive">
                        <table class="table table-condensed table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <td class="col-sm-2">{{ trans('pm.to') }}</td>
                            <td class="col-sm-6">{{ trans('pm.subject') }}</td>
                            <td class="col-sm-2">{{ trans('pm.sent-at') }}</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pms as $p)
                            <tr>
                                <td class="col-sm-2"><a
                                            href="{{ route('profile', ['username' => $p->receiver->username, 'id' => $p->receiver->id]) }}"
                                            title="">{{ $p->receiver->username}}</a></td>
                                <td class="col-sm-5"><a
                                            href="{{ route('message', ['id' => $p->id]) }}">{{ $p->subject }}</a>
                                </td>
                                <td class="col-sm-2">{{ $p->created_at->diffForHumans() }}</td>
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

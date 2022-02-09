@extends('layout.default')

@section('breadcrumb')
    <li class="active">
        <a href="{{ route('inbox') }}">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                {{ __('pm.inbox') }}
            </span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            @include('partials.pmmenu')
            <div class="col-md-10">
                <div class="block">
                    <div class="row">
                        <div class="col-md-8 col-xs-5">
                            <div class="btn-group">
                                <form action="{{ route('mark-all-read') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" id="mark-all-read" class="btn btn-success dropdown-toggle"
                                            data-toggle="tooltip" data-placement="top"
                                            data-original-title="{{ __('pm.mark-all-read') }}">
                                        <i class="{{ config('other.font-awesome') }} fa-eye"></i>
                                    </button>
                                </form>
                                <a href="{{ route('inbox') }}">
                                    <button type="button" id="btn_refresh" class="btn btn-primary dropdown-toggle"
                                            data-toggle="tooltip" data-placement="top"
                                            data-original-title="{{ __('pm.refresh') }}"><i
                                                class="{{ config('other.font-awesome') }} fa-sync-alt"></i></button>
                                </a>
                                <form role="form" method="POST" action="{{ route('empty-inbox') }}"
                                      style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger dropdown-toggle">
                                        <i class="{{ config('other.font-awesome') }} fa-trash"></i> {{ __('pm.empty-inbox') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-7">
                            <div class="input-group pull-right">
                                <form role="form" method="POST" action="{{ route('searchPMInbox') }}">
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
                                <td class="col-sm-2">{{ __('pm.from') }}</td>
                                <td class="col-sm-5">{{ __('pm.subject') }}</td>
                                <td class="col-sm-2">{{ __('pm.received-at') }}</td>
                                <td class="col-sm-2">{{ __('pm.read') }}</td>
                                <td class="col-sm-2">{{ __('pm.delete') }}</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($pms as $p)
                                <tr>
                                    <td class="col-sm-2">
                                        <a href="{{ route('users.show', ['username' => $p->sender->username]) }}">{{ $p->sender->username }}
                                        </a>
                                    </td>
                                    <td class="col-sm-5">
                                        <a href="{{ route('message', ['id' => $p->id]) }}">
                                            {{ $p->subject }}
                                        </a>
                                    </td>
                                    <td class="col-sm-2">
                                        {{ $p->created_at->diffForHumans() }}
                                    </td>
                                    @if ($p->read == 0)
                                        <td class="col-sm-2">
                                                <span class='label label-danger'>
                                                    {{ __('pm.unread') }}
                                                </span>
                                        </td>
                                    @else ($p->read >= 1)
                                        <td class="col-sm-2">
                                                <span class='label label-success'>
                                                    {{ __('pm.read') }}
                                                </span>
                                        </td>
                                    @endif
                                    <td class="col-sm-2">
                                        <form role="form" method="POST"
                                              action="{{ route('delete-pm', ['id' => $p->id]) }}">
                                            @csrf
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

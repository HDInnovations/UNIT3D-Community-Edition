@extends('layout.default')

@section('title')
    <title>Application - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Application - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.applications.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.applications') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.applications.show', ['id' => $application->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.applications') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="table-responsive">
                <table class="table table-condensed table-bordered table-striped">
                    <tbody>
                    <tr>
                        <td>
                            <strong>{{ __('common.email') }}</strong>
                        </td>
                        <td>
                            {{ $application->email }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>{{ __('staff.application-type') }}</strong>
                        </td>
                        <td>
                            {{ $application->type }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>{{ __('common.created_at') }}</strong>
                        </td>
                        <td>
                            {{ $application->created_at->toDayDateTimeString() }}
                            ({{ $application->created_at->diffForHumans() }})
                        </td>
                    </tr>
                    <tr>
                        <td class="col-md-2">
                            <strong>{{ __('staff.application-image-proofs') }}</strong>
                        </td>
                        <td>
                            @foreach($application->imageProofs as $key => $img_proof)
                                <a href="{{ $img_proof->image }}" target="_blank">
                                    <button type="button"
                                            class="btn btn-sm btn-info">{{ __('staff.application-image-proofs') }} {{ ++$key }}</button>
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>{{ __('user.profile') }} {{ __('staff.links') }}</strong>
                        </td>
                        <td>
                            @foreach($application->urlProofs as $key => $url_proof)
                                <li><a href="{{ $url_proof->url }}"
                                       target="_blank">{{ __('user.profile') }} {{ __('staff.links') }} {{ ++$key }}</a></li>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>{{ __('staff.application-referrer') }}</strong>
                        </td>
                        <td>
                            <div class="form-group">
                                <label>
                                    <textarea name="referrer" cols="30" rows="10" class="form-control"
                                              disabled="">{{ $application->referrer }}</textarea>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>{{ __('common.status') }}</strong>
                        </td>
                        <td>
                            @if ($application->status == 0)
                                <span class="text-warning">{{ __('request.pending') }}</span>
                            @elseif ($application->status == 1)
                                <span class="text-success">{{ __('request.approve') }}</span>
                            @else
                                <span class="text-danger">{{ __('request.reject') }}</span>
                            @endif
                        </td>
                    </tr>
                    @if($application->status != 0)
                        <tr>
                            <td>
                                <strong>{{ __('common.moderated-by') }}</strong>
                            </td>
                            <td>{{ $application->moderated->username }}</td>
                        </tr>
                    @else
                        <tr>
                            <td>
                                <strong>{{ __('common.action') }}</strong>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                        data-target="#approve-application"><i
                                            class="{{ config('other.font-awesome') }} fa-check"></i> {{ __('request.approve') }}
                                </button>

                                <div id="approve-application" class="modal fade" role="dialog">
                                    <form method="POST"
                                          action="{{ route('staff.applications.approve', ['id' => $application->id]) }}">
                                        @csrf
                                        <div class="modal-dialog{{ modal_style() }}">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close"
                                                            data-dismiss="modal">&times;
                                                    </button>
                                                    <h4 class="modal-title">
                                                        {{ __('request.approve') }}
                                                        {{ __('common.this') }}
                                                        {{ __('staff.application') }}
                                                    </h4>
                                                </div>
                                                <div class="modal-body">
                                                    <input id="email" name="email" type="hidden"
                                                           value="{{ $application->email }}">
                                                    <div class="form-group">
                                                        <label for="message">{{ __('common.message') }}</label>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="approve"></label>
                                                        <textarea class="form-control" rows="5" cols="50" name="approve"
                                                                  id="approve">Application Approved!</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-success" type="submit">
                                                        <i class="{{ config('other.font-awesome') }} fa-check"></i>
                                                        {{ __('request.approve') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#deny-application"><i
                                            class="{{ config('other.font-awesome') }} fa-times"></i> {{ __('request.reject') }}
                                </button>

                                <div id="deny-application" class="modal fade" role="dialog">
                                    <form method="POST"
                                          action="{{ route('staff.applications.reject', ['id' => $application->id]) }}">
                                        @csrf
                                        <div class="modal-dialog{{ modal_style() }}">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close"
                                                            data-dismiss="modal">&times;
                                                    </button>
                                                    <h4 class="modal-title">
                                                        {{ __('request.reject') }}
                                                        {{ __('common.this') }}
                                                        {{ __('staff.application') }}
                                                    </h4>
                                                </div>
                                                <div class="modal-body">
                                                    <input id="email" name="email" type="hidden"
                                                           value="{{ $application->email }}">
                                                    <div class="form-group">
                                                        <label for="message">{{ __('common.message') }}</label>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="deny"></label>
                                                        <textarea class="form-control" rows="5" cols="50" name="deny"
                                                                  id="deny">Insufficient Proofs.</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-danger" type="submit">
                                                        <i class="{{ config('other.font-awesome') }} fa-times"></i>
                                                        {{ __('request.reject') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

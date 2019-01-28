@extends('layout.default')

@section('title')
    <title>Application - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.applications.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Applications</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.applications.show', ['id' => $application->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Application</span>
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
                            <strong>Email</strong>
                        </td>
                        <td>
                            {{ $application->email }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Type</strong>
                        </td>
                        <td>
                            {{ $application->type }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Created On</strong>
                        </td>
                        <td>
                            {{ $application->created_at->toDayDateTimeString() }}
                            ({{ $application->created_at->diffForHumans() }})
                        </td>
                    </tr>
                    <tr>
                        <td class="col-md-2">
                            <strong>Profile Images<br>(Click For Preview)</strong>
                        </td>
                        <td>
                            @foreach($application->imageProofs as $key => $img_proof)
                                <div id="image-{{ $img_proof->id }}" class="modal fade" aria-labelledby="my-modalLabel" aria-hidden="true" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" data-dismiss="modal">
                                        <div class="modal-content"  >
                                            <div class="modal-body">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                <img src="{{ $img_proof->image }}" class="img-responsive" style="width: 100%;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <li><button id="show-img" type="button" class="btn btn-default" data-toggle="modal" data-target="#image-{{ $img_proof->id }}">Profile Image {{ ++$key }}</button></li>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Profile Links<br>(Click For Popup)</strong>
                        </td>
                        <td>
                            @foreach($application->urlProofs as $key => $url_proof)
                                <li><a href="{{ $url_proof->url }}" target="_blank">Profile Link {{ ++$key }}</a></li>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Status</strong>
                        </td>
                        <td>
                            @if ($application->status == 0)
                                <span class="text-warning">PENDING</span>
                            @elseif ($application->status == 1)
                                <span class="text-success">APPROVED</span>
                            @else
                                <span class="text-danger">REJECTED</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Action</strong>
                        </td>
                        <td>
                            <a href="{{ route('staff.applications.approve', ['id' => $application->id]) }}"
                               class="btn btn-xs btn-success">
                                <i class="{{ config('other.font-awesome') }} fa-check"></i> Approve
                            </a>
                            <a href="{{ route('staff.applications.reject', ['id' => $application->id]) }}"
                               class="btn btn-xs btn-danger">
                                <i class="{{ config('other.font-awesome') }} fa-times"></i> Reject
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

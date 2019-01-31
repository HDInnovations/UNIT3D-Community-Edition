@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('get_notifications') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('notification.notifications')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="col-md-12 page">
            <div class="header gradient teal">
                <div class="inner_content">
                    <div class="page-title">
                        <h1>@lang('notification.notifications')</h1>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <div class="pull-right">
                    <a href="{{ route('massRead_notifications') }}">
                        <button type="button" class="btn btn btn-success" data-toggle="tooltip"
                                data-original-title="@lang('notification.mark-all-read')"><i
                                    class="{{ config('other.font-awesome') }} fa-eye"></i> @lang('notification.mark-all-read')</button>
                    </a>
                    <a href="{{ route('delete_notifications') }}">
                        <button type="button" class="btn btn btn-danger" data-toggle="tooltip"
                                data-original-title="@lang('notification.delete-all')"><i
                                    class="{{ config('other.font-awesome') }} fa-times"></i> @lang('notification.delete-all')</button>
                    </a>
                </div>
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>@lang('notification.title')</th>
                        <th>@lang('notification.message')</th>
                        <th>@lang('notification.date')</th>
                        <th>@lang('notification.read')</th>
                        <th>@lang('notification.delete')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($notification as $n)
                        <tr>
                            <td>
                                <a href="{{ route('show_notification', ['id' => $n->id]) }}" class="clearfix">
                                    <span class="notification-title">{{ $n->data['title'] }}</span>
                                </a>
                            </td>
                            <td>
                                <span class="notification-message">{{ $n->data['body'] }}</span>
                            </td>
                            <td>
                                <span class="notification-ago">{{ $n->created_at->diffForHumans() }}</span>
                            </td>
                            <td>
                                <a href="{{ route('read_notification', ['id' => $n->id]) }}">
                                    <button type="button" class="btn btn-xxs btn-success" data-toggle="tooltip"
                                            data-original-title="@lang('notification.mark-read')"
                                            @if ($n->read_at != null) disabled @endif><i class="{{ config('other.font-awesome') }} fa-eye"></i></button>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('delete_notification', ['id' => $n->id]) }}">
                                    <button type="button" class="btn btn-xxs btn-danger" data-toggle="tooltip"
                                            data-original-title="@lang('notification.delete')"><i
                                                class="{{ config('other.font-awesome') }} fa-times"></i></button>
                                </a>
                            </td>
                        </tr>
                    @empty
                        @lang('notification.no-notifications').
                    @endforelse
                    </tbody>
                </table>
                <div class="text-center">{{ $notification->links() }}</div>
            </div>
        </div>
    </div>
@endsection

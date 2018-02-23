@extends('layout.default')

@section('breadcrumb')
<li>
    <a href="{{ route('get_notifications') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('notification.notifications') }}</span>
    </a>
</li>
@endsection

@section('content')
<div class="container box">
	<div class="col-md-12 page">
		<div class="header gradient teal">
			<div class="inner_content">
				<div class="page-title"><h1><i class="fa fa-bell-o"></i>{{ trans('notification.notifications') }}</h1></div>
			</div>
		</div>
        <div class="table-responsive">
          <div class="pull-right">
              <a href="{{ route('massRead_notifications') }}"><button type="button" class="btn btn btn-success" data-toggle="tooltip" title="" data-original-title="{{ trans('notification.mark-all-read') }}"><i class="fa fa-eye"></i> {{ trans('notification.mark-all-read') }}</button></a>
              <a href="{{ route('delete_notifications') }}"><button type="button" class="btn btn btn-danger" data-toggle="tooltip" title="" data-original-title="{{ trans('notification.delete-all') }}"><i class="fa fa-times"></i> {{ trans('notification.delete-all') }}</button></a>
          </div>
          <table class="table table-condensed table-striped table-bordered">
            <thead>
              <tr>
                <th>{{ trans('notification.title') }}</th>
                <th>{{ trans('notification.message') }}</th>
                <th>{{ trans('notification.date') }}</th>
                <th>{{ trans('notification.read') }}</th>
                <th>{{ trans('notification.delete') }}</th>
              </tr>
            </thead>
            <tbody>
              @forelse($notification as $n)
              <tr>
                <td>
                    <a href="{{ $n->data['url'] }}" class="clearfix">
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
                    <a href="{{ route('read_notification', array('id' => $n->id)) }}"><button type="button" class="btn btn-xxs btn-success" data-toggle="tooltip" title="" data-original-title="{{ trans('notification.mark-read') }}" @if($n->read_at != null) disabled @endif><i class="fa fa-eye"></i></button></a>
                </td>
                <td>
                    <a href="{{ route('delete_notification', array('id' => $n->id)) }}"><button type="button" class="btn btn-xxs btn-danger" data-toggle="tooltip" title="" data-original-title="{{ trans('notification.delete') }}"><i class="fa fa-times"></i></button></a>
                </td>
              </tr>
              @empty
                  {{ trans('notification.no-notifications') }}.
              @endforelse
            </tbody>
          </table>
        </div>
    </div>
</div>
@endsection

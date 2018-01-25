@extends('layout.default')

@section('breadcrumb')
<li>
    <a href="{{ route('get_notifications') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Notifications</span>
    </a>
</li>
@stop

@section('content')
<div class="container box">
	<div class="col-md-12 page">
		<div class="header gradient teal">
			<div class="inner_content">
				<div class="page-title"><h1><i class="fa fa-bell-o"></i>Notifications</h1></div>
			</div>
		</div>
        <div class="table-responsive">
          <div class="pull-right">
              <a href="{{ route('massRead_notifications') }}"><button type="button" class="btn btn btn-success" data-toggle="tooltip" title="" data-original-title="Mark All As Read"><i class="fa fa-eye"></i> Mark All Read</button></a>
              <a href="{{ route('delete_notifications') }}"><button type="button" class="btn btn btn-danger" data-toggle="tooltip" title="" data-original-title="Delete All Notifications"><i class="fa fa-times"></i> Delete All</button></a>
          </div>
          <table class="table table-condensed table-striped table-bordered">
            <thead>
              <tr>
                <th>Title</th>
                <th>Message</th>
                <th>Date</th>
                <th>Read</th>
                <th>Delete</th>
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
                    <a href="{{ route('read_notification', array('id' => $n->id)) }}"><button type="button" class="btn btn-xxs btn-success" data-toggle="tooltip" title="" data-original-title="Mark As Read" @if($n->read_at != null) disabled @endif><i class="fa fa-eye"></i></button></a>
                </td>
                <td>
                    <a href="{{ route('delete_notification', array('id' => $n->id)) }}"><button type="button" class="btn btn-xxs btn-danger" data-toggle="tooltip" title="" data-original-title="Delete"><i class="fa fa-times"></i></button></a>
                </td>
              </tr>
              @empty
                  There are no notifications found.
              @endforelse
            </tbody>
          </table>
        </div>
    </div>
</div>
@stop

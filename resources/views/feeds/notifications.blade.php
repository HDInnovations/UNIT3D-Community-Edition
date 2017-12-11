@extends('layout.default')

@section('breadcrumb')
<li class="active">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Notification Feed</span>
</li>
@stop

@section('content')
    <div class="container">
        @if ($activities)
            <div class="panel panel-default">
                <div class="panel-heading">
                    Notification Feed
                </div>

                <div class="panel-body">
                    @foreach ($activities as $activity)
                        @foreach ($activity['activities'] as $activity)
                            @include('stream-laravel::render_activity', array('aggregated_activity'=>$activity, 'prefix'=>'notification'))
                        @endforeach
                    @endforeach
                </div>
            </div>
        @else
        <p>You don't have any notifications</p>
        @endif
    </div>
@endsection

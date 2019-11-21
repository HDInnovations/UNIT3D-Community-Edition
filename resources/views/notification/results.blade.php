<div class="table-responsive">
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
            @forelse($notifications as $notification)
                <tr>
                    <td>
                        <a href="{{ route('notifications.show', ['id' => $notification->id]) }}" class="clearfix">
                            <span class="notification-title">{{ $notification->data['title'] }}</span>
                        </a>
                    </td>
                    <td>
                        <span class="notification-message">{{ $notification->data['body'] }}</span>
                    </td>
                    <td>
                        <span class="notification-ago">{{ $notification->created_at->diffForHumans() }}</span>
                    </td>
                    <td>
                        <form action="{{ route('notifications.update', ['id' => $notification->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-xxs btn-success" data-toggle="tooltip"
                                data-original-title="@lang('notification.mark-read')" @if ($notification->read_at != null)
                                disabled @endif>
                                <i class="{{ config('other.font-awesome') }} fa-eye"></i>
                            </button>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('notifications.destroy', ['id' => $notification->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-xxs btn-danger" data-toggle="tooltip"
                                data-original-title="@lang('notification.delete')">
                                <i class="{{ config('other.font-awesome') }} fa-times"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                @lang('notification.no-notifications').
            @endforelse
        </tbody>
    </table>
    <div class="text-center">{{ $notifications->links() }}</div>
</div>

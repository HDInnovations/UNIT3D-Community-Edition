<div>
    <div class="container box">
        <div class="text-center">
            <h3>{{ __('notification.filter-by-type') }}</h3>
        </div>
        <div class="form-group text-center">
            <div class="col-md-12">
                <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" wire:model.prefetch="bon_gifts" value="1">
                        <i class="{{ config('other.font-awesome') }} fa-coins text-success"></i>
                        {{ __('notification.bon-gifts') }}
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" wire:model.prefetch="comment" value="1">
                        <i class="{{ config('other.font-awesome') }} fa-comments text-success"></i>
                        {{ __('common.comments') }}
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" wire:model.prefetch="comment_tags" value="1">
                        <i class="{{ config('other.font-awesome') }} fa-tag text-success"></i>
                        {{ __('notification.comment-tags') }}
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" wire:model.prefetch="followers" value="1">
                        <i class="{{ config('other.font-awesome') }} fa-smile-plus text-success"></i>
                        {{ __('user.followers') }}
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" wire:model.prefetch="posts" value="1">
                        <i class="{{ config('other.font-awesome') }} fa-comment-dots text-success"></i>
                        {{ __('common.posts') }}
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" wire:model.prefetch="post_tags" value="1">
                        <i class="{{ config('other.font-awesome') }} fa-tag text-success"></i>
                        {{ __('notification.post-tags') }}
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" wire:model.prefetch="post_tips" value="1">
                        <i class="{{ config('other.font-awesome') }} fa-coins text-success"></i>
                        {{ __('notification.post-tips') }}
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" wire:model.prefetch="request_bounties" value="1">
                        <i class="{{ config('other.font-awesome') }} fa-crosshairs text-success"></i>
                        {{ __('notification.request-bounties') }}
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" wire:model.prefetch="request_claims" value="1">
                        <i class="{{ config('other.font-awesome') }} fa-check-circle text-success"></i>
                        {{ __('notification.request-claims') }}
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" wire:model.prefetch="request_fills" value="1">
                        <i class="{{ config('other.font-awesome') }} fa-check-square text-success"></i>
                        {{ __('notification.request-fills') }}
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" wire:model.prefetch="request_approvals" value="1">
                        <i class="{{ config('other.font-awesome') }} fa-clipboard-check text-success"></i>
                        {{ __('notification.request-approvals') }}
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" wire:model.prefetch="request_rejections" value="1">
                        <i class="{{ config('other.font-awesome') }} fa-times text-success"></i>
                        {{ __('notification.request-rejections') }}
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" wire:model.prefetch="request_unclaims" value="1">
                        <i class="{{ config('other.font-awesome') }} fa-times-square text-success"></i>
                        {{ __('notification.request-unclaims') }}
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" wire:model.prefetch="reseed_requests" value="1">
                        <i class="{{ config('other.font-awesome') }} fa-question text-success"></i>
                        {{ __('notification.reseed-requests') }}
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" wire:model.prefetch="thanks" value="1">
                        <i class="{{ config('other.font-awesome') }} fa-heart text-success"></i>
                        {{ __('torrent.thanks') }}
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" wire:model.prefetch="upload_tips" value="1">
                        <i class="{{ config('other.font-awesome') }} fa-coins text-success"></i>
                        {{ __('bon.tips') }}
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" value="topics">
                        <i class="{{ config('other.font-awesome') }} fa-comment-alt-check text-success"></i>
                        {{ __('common.topics') }}
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" wire:model.prefetch="unfollows" value="1">
                        <i class="{{ config('other.font-awesome') }} fa-frown text-success"></i>
                        {{ __('notification.unfollows') }}
                    </label>
                </span>
                <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" wire:model.prefetch="uploads" value="1">
                        <i class="{{ config('other.font-awesome') }} fa-upload text-success"></i>
                        {{ __('user.uploads') }}
                    </label>
                </span>
            </div>
        </div>

        <div class="text-center">
            <form action="{{ route('notifications.updateall') }}" method="POST" style="display: inline-block;">
                @csrf
                <button type="submit" class="btn btn btn-success" data-toggle="tooltip"
                        data-original-title="{{ __('notification.mark-all-read') }}">
                    <i class="{{ config('other.font-awesome') }} fa-eye"></i> {{ __('notification.mark-all-read') }}
                </button>
            </form>

            <form action="{{ route('notifications.destroyall') }}" method="POST" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn btn-danger" data-toggle="tooltip"
                        data-original-title="{{ __('notification.delete-all') }}">
                    <i class="{{ config('other.font-awesome') }} fa-times"></i> {{ __('notification.delete-all') }}
                </button>
            </form>
        </div>
    </div>

    <div class="container-fluid">
        <div class="block">
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>{{ __('notification.title') }}</th>
                        <th>{{ __('notification.message') }}</th>
                        <th>{{ __('notification.date') }}</th>
                        <th>{{ __('notification.read') }}</th>
                        <th>{{ __('notification.delete') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($notifications as $notification)
                        <tr>
                            <td>
                                <a href="{{ route('notifications.show', ['id' => $notification->id]) }}"
                                   class="clearfix">
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
                                <form action="{{ route('notifications.update', ['id' => $notification->id]) }}"
                                      method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-xxs btn-success" data-toggle="tooltip"
                                            data-original-title="{{ __('notification.mark-read') }}"
                                            @if ($notification->read_at != null)
                                                disabled @endif>
                                        <i class="{{ config('other.font-awesome') }} fa-eye"></i>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('notifications.destroy', ['id' => $notification->id]) }}"
                                      method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xxs btn-danger" data-toggle="tooltip"
                                            data-original-title="{{ __('notification.delete') }}">
                                        <i class="{{ config('other.font-awesome') }} fa-times"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        {{ __('notification.no-notifications') }}.
                    @endforelse
                    </tbody>
                </table>
                <div class="text-center">{{ $notifications->links() }}</div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-chat shoutbox torrent-controls">
    <div class="panel-heading">
        <h4>
            <i class="{{ config("other.font-awesome") }} fa-hammer-war"></i> {{ __('torrent.moderation') }}
        </h4>
    </div>
    <div class="table-responsive">
        <table class="table table-condensed table-bordered table-striped">
            <tbody>
            <tr>
                <td style="display: flex; justify-content: space-around; padding: 10px 0">
                    @if (auth()->user()->group->is_modo || auth()->user()->id === $torrent->user->id)
                        <div class="torrent-editing-controls">
                            <a class="btn btn-warning btn-xs" href="{{ route('edit_form', ['id' => $torrent->id]) }}"
                               role="button">
                                <i class="{{ config('other.font-awesome') }} fa-pencil-alt"></i> {{ __('common.edit') }}
                            </a>
                            @if (auth()->user()->group->is_modo || ( auth()->user()->id === $torrent->user->id && Illuminate\Support\Carbon::now()->lt($torrent->created_at->addDay())))
                                <button class="btn btn-danger btn-xs" data-toggle="modal"
                                        data-target="#modal_torrent_delete">
                                    <i class="{{ config('other.font-awesome') }} fa-times"></i> {{ __('common.delete') }}
                                </button>
                            @endif
                        </div>
                    @endif

                    @if (auth()->user()->group->is_modo || auth()->user()->group->is_internal)
                        <div class="torrent-internal-controls">
                            @if ($torrent->free == 0)
                                <button data-target="#freeleech-{{ $torrent->id }}" data-toggle="modal"
                                        class="btn btn-xs btn-success">
                                    <i class="{{ config('other.font-awesome') }} fa-star"></i> {{ __('torrent.grant') }} {{ __('torrent.freeleech') }}
                                </button>
                            @elseif ($torrent->featured)
                                <form action="{{ route('torrent_fl', ['id' => $torrent->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <div x-data="{total_value:0}" style="display: flex; margin-bottom: 5px;">
                                        <input disabled type="range"
                                               x-model="total_value" min="0" max="100" step="25" list="steplist"
                                               name="freeleech" value="{{ $torrent->free ?? '0' }}"
                                        />
                                        <datalist id="steplist">
                                            <option>0</option>
                                            <option>25</option>
                                            <option>50</option>
                                            <option>75</option>
                                            <option>100</option>
                                        </datalist>
                                        <button type="submit" class="btn btn-xs btn-danger">
                                            <i class="{{ config('other.font-awesome') }} fa-star"></i> {{ __('torrent.revoke') }} {{ __('torrent.freeleech') }}
                                        </button>
                                    </div>
                                </form>
                            @else
                                <form action="{{ route('torrent_fl', ['id' => $torrent->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <div x-data="{total_value:0}" style="display: flex; margin-bottom: 5px;">
                                        <input disabled type="range"
                                               x-model="total_value" min="0" max="100" step="25" list="steplist"
                                               name="freeleech" value="{{ $torrent->free ?? '0' }}"
                                        />
                                        <datalist id="steplist">
                                            <option>0</option>
                                            <option>25</option>
                                            <option>50</option>
                                            <option>75</option>
                                            <option>100</option>
                                        </datalist>
                                        <button type="submit" class="btn btn-xs btn-danger">
                                            <i class="{{ config('other.font-awesome') }} fa-star"></i> {{ __('torrent.revoke') }} {{ $torrent->free ?? '0' }}
                                            % {{ __('torrent.freeleech') }}
                                        </button>
                                    </div>
                                </form>
                            @endif

                            @if ($torrent->doubleup == 0)
                                <button data-target="#doubleup-{{ $torrent->id }}" data-toggle="modal"
                                        class="btn btn-xs btn-success">
                                    <i class="{{ config('other.font-awesome') }} fa-chevron-double-up"></i> {{ __('torrent.grant') }} {{ __('torrent.double-upload') }}
                                </button>
                            @else
                                <form action="{{ route('torrent_doubleup', ['id' => $torrent->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-danger">
                                        <i class="{{ config('other.font-awesome') }} fa-chevron-double-up"></i> {{ __('torrent.revoke') }} {{ __('torrent.double-upload') }}
                                    </button>
                                </form>
                            @endif

                            @if ($torrent->sticky == 0)
                                <form action="{{ route('torrent_sticky', ['id' => $torrent->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-success">
                                        <i class="{{ config('other.font-awesome') }} fa-thumbtack"></i> {{ __('torrent.sticky') }}
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('torrent_sticky', ['id' => $torrent->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-danger">
                                        <i class="{{ config('other.font-awesome') }} fa-thumbtack"></i> {{ __('torrent.unsticky') }}
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('bumpTorrent', ['id' => $torrent->id]) }}" method="POST"
                                  style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-xs btn-primary">
                                    <i class="{{ config('other.font-awesome') }} fa-arrow-to-top"></i> {{ __('torrent.bump') }}
                                </button>
                            </form>

                            @if ($torrent->featured == 0)
                                <form role="form" method="POST"
                                      action="{{ route('torrent_feature', ['id' => $torrent->id]) }}"
                                      style="display: inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-default">
                                        <i class='{{ config('other.font-awesome') }} fa-certificate'></i> {{ __('torrent.feature') }}
                                    </button>
                                </form>
                            @else
                                <form role="form" method="POST"
                                      action="{{ route('torrent_revokefeature', ['id' => $torrent->id]) }}"
                                      style="display: inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-danger">
                                        <i class='{{ config('other.font-awesome') }} fa-certificate'></i> {{ __('torrent.revokefeatured') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif

                    @if (auth()->user()->group->is_modo)
                        <div class="torrent-moderation-controls">
                            <form role="form" method="POST"
                                  action="{{ route('staff.moderation.update', ['id' => $torrent->id]) }}"
                                  style="display: inline-block;">
                                @csrf
                                <input type="hidden" name="old_status" value="{{ $torrent->status }}">
                                <input type="hidden" name="status" value="1">
                                <button type="submit"
                                        class="btn btn-labeled btn-xs btn-success @if ($torrent->isApproved()) disabled @endif">
                                    <i class="{{ config('other.font-awesome') }} fa-thumbs-up"></i> {{ __('common.moderation-approve') }}
                                </button>
                            </form>

                            <button data-target="#postpone-{{ $torrent->id }}" data-toggle="modal"
                                    class="btn btn-labeled btn-warning btn-xs @if ($torrent->isPostponed()) disabled @endif">
                                <i class="{{ config('other.font-awesome') }} fa-pause"></i> {{ __('common.moderation-postpone') }}
                            </button>

                            <button data-target="#reject-{{ $torrent->id }}" data-toggle="modal"
                                    class="btn btn-labeled btn-danger btn-xs @if ($torrent->isRejected()) disabled @endif">
                                <i class="{{ config('other.font-awesome') }} fa-thumbs-down"></i> {{ __('common.moderation-reject') }}
                            </button>

                            <span>
                                [{{ __('common.moderated-by') }}
                                <a href="{{ route('users.show', ['username' => $torrent->moderated->username]) }}"
                                   style="color:{{ $torrent->moderated->group->color }};">
                                    <i class="{{ $torrent->moderated->group->icon }}"
                                       data-toggle="tooltip"
                                       data-original-title="{{ $torrent->moderated->group->name }}"></i> {{ $torrent->moderated->username }}
                                </a>]
                            </span>
                        </div>
                    @endif
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
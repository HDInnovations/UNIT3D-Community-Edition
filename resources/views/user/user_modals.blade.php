{{-- Gift Modal --}}
<div class="modal fade" id="modal_user_gift" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">@lang('bon.gift-to'): {{ $user->username }}</h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                    <form role="form" method="POST" action="{{ route('bonus_send_gift') }}">
                        @csrf
                        <input type="hidden" name="dest" value="profile" />
                        <input type="hidden" name="to_username" value="{{ $user->username }}" />
                        <div class="form-group">
                            <label for="bonus_points">@lang('bon.amount')</label>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="@lang('common.enter') {{ strtolower(trans('common.amount')) }}" name="bonus_points" type="number" id="bonus_points" required>
                        </div>
                        <div class="form-group">
                            <label for="bonus_message">@lang('pm.message')</label>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="bonus_message" cols="50" rows="10" id="bonus_message"></textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-small btn-primary" type="submit" value="@lang('bon.gift')">
                        </div>
                    </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-danger" type="button" data-dismiss="modal">Close</button>
                </div>
        </div>
    </div>
</div>

{{-- Message Modal --}}
<div class="modal fade" id="modal_user_pm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">@lang('pm.send-to'): {{ $user->username }}</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form role="form" method="POST" action="{{ route('send-pm') }}">
                        @csrf
                        <input type="hidden" name="dest" value="profile" />
                        <input type="hidden" name="receiver_id" value="{{ $user->id }}" />
                        <div class="form-group">
                            <label for="">@lang('pm.subject')</label>
                        </div>
                        <div class="form-group">
                            <input name="subject" class="form-control" placeholder="@lang('pm.enter-subject')"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="">@lang('pm.message')</label>
                        </div>
                        <div class="form-group">
                            <textarea id="message" name="message" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-small btn-primary" type="submit" value="@lang('pm.send')">
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-danger" type="button" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Note Modal --}}
<div class="modal fade" id="modal_user_note" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="container-fluid">
                <form role="form" method="POST" action="{{ route('postNote', ['username' => $user->username, 'id' => $user->id]) }}">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Note User: {{ $user->username }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="report_reason">Note</label>
                        </div>
                        <div class="form-group">
                            <textarea name="message" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-danger" type="submit" value="Save">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Ban Modal --}}
<div class="modal fade" id="modal_user_ban" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="container-fluid">
            <form role="form" method="POST" action="{{ route('ban', ['username' => $user->username, 'id' => $user->id]) }}">
            @csrf
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Ban User: {{ $user->username }}</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="report_reason">Reason</label>
                </div>
                <div class="form-group">
                        <textarea class="form-control" rows="5" name="ban_reason" cols="50" id="ban_reason"></textarea>
                </div>
                <div class="form-group">
                    <input class="btn btn-danger" type="submit" value="Ban">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close</button>
            </div>
            </form>
            </div>
        </div>
    </div>
</div>

{{-- Unban Modal --}}
<div class="modal fade" id="modal_user_unban" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="container-fluid">
            <form role="form" method="POST" action="{{ route('unban', ['username' => $user->username, 'id' => $user->id]) }}">
            @csrf
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Unban User: {{ $user->username }}</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="report_reason">UnBan Reason</label>
                </div>
                <div class="form-group">
                        <textarea class="form-control" rows="5" name="unban_reason" cols="50"
                                  id="unban_reason"></textarea>
                </div>
                <div class="form-group">
                    <label for="report_reason">New Group</label>
                </div>
                <div class="form-group">
                        <select name="group_id" class="form-control">
                            <option value="{{ $user->group->id }}">{{ $user->group->name }} (Default)</option>
                            @foreach ($groups as $g)
                                <option value="{{ $g->id }}">{{ $g->name }}</option>
                            @endforeach
                        </select>
                </div>
                <div class="form-group">
                        <input class="btn btn-primary" type="submit" value="Unban">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close</button>
            </div>
            </form>
            </div>
        </div>
    </div>
</div>

{{-- Report Modal --}}
<div class="modal fade" id="modal_user_report" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Report User: {{ $user->username }}</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                <form class="form-horizontal" role="form" method="POST" action="{{ route('report_user', ['username' => $user->username, 'id' => $user->id]) }}">
                    @csrf
                    <div class="form-group">
                        <label for="report_reason">Reason</label>
                    </div>
                    <div class="form-group">
                            <textarea class="form-control" rows="5" name="message" cols="50" id="message"></textarea>
                    </div>
                    <div class="form-group">
                            <input class="btn btn-danger" type="submit" value="Report">
                    </div>
                </form>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Delete User Modal --}}
<div class="modal fade" id="modal_user_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Are you sure you want to delete {{ $user->username }}?</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                <div class="form-group">
                        <a href="{{ route('user_delete', ['username' => $user->username, 'id' => $user->id]) }}"><input
                                    class="btn btn-danger" type="submit" value="Yes, Delete"></a>
                </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

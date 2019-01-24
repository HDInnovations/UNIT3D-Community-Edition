{{-- Ban Modal --}}
<div class="modal fade" id="modal_user_ban" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <meta charset="utf-8">
            <title>Ban User: {{ $user->username }}</title>
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
                    <label for="file_name" class="col-sm-2 control-label">User</label>
                    <div class="col-sm-10">
                        <p class="form-control-static">{{ $user->username }}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="report_reason" class="col-sm-2 control-label">Reason</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="5" name="ban_reason" cols="50" id="ban_reason"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <input class="btn btn-danger" type="submit" value="Ban">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- Unban Modal --}}
<div class="modal fade" id="modal_user_unban" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <meta charset="utf-8">
            <title>Unban User: {{ $user->username }}</title>
            <form role="form" method="POST" action="{{ route('unban', ['username' => $user->username, 'id' => $user->id]) }}">
            @csrf
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Unban User: {{ $user->username }}</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="file_name" class="col-sm-3 control-label">User</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">{{ $user->username }}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="report_reason" class="col-sm-3 control-label">UnBan Reason</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" rows="5" name="unban_reason" cols="50"
                                  id="unban_reason"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="report_reason" class="col-sm-3 control-label">New Group</label>
                    <div class="col-sm-9">
                        <select name="group_id" class="form-control">
                            <option value="{{ $user->group->id }}">{{ $user->group->name }} (Default)</option>
                            @foreach ($groups as $g)
                                <option value="{{ $g->id }}">{{ $g->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <input class="btn btn-primary" type="submit" value="Unban">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- Report Modal --}}
<div class="modal fade" id="modal_user_report" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <meta charset="utf-8">
            <title>Report User: {{ $user->username }}</title>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Report User: {{ $user->username }}</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" method="POST" action="{{ route('report_user', ['username' => $user->username, 'id' => $user->id]) }}">
                    @csrf
                    <div class="form-group">
                        <label for="file_name" class="col-sm-2 control-label">User</label>
                        <div class="col-sm-10">
                            <input id="title" name="title" type="hidden" value="{{ $user->username }}">
                            <p class="form-control-static">{{ $user->username }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="report_reason" class="col-sm-2 control-label">Reason</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="5" name="message" cols="50" id="message"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input class="btn btn-danger" type="submit" value="Report">
                        </div>
                    </div>
                </form>
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
            <meta charset="utf-8">
            <title>Delete User: {{ $user->username }}</title>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Are you sure to delete {{ $user->username }}</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="file_name" class="col-sm-2 control-label">User</label>
                    <div class="col-sm-10">
                        <p class="form-control-static">{{ $user->username }}</p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <a href="{{ route('user_delete', ['username' => $user->username, 'id' => $user->id]) }}"><input
                                    class="btn btn-danger" type="submit" value="Delete"></a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

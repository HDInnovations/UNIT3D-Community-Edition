{{--Edit Chatroom--}}
<div id="editChatroom-{{ $chatroom->id }}" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header" style="text-align: center;">
                <h3>Edit Chatroom ({{ $chatroom->name }})</h3>
            </div>

            <form class="form-horizontal" role="form" method="POST" action="{{ route('staff.rooms.update', ['id' => $chatroom->id]) }}">
            @csrf
            <div class="modal-body"  style="text-align: center;">
                <h4>Please enter the new name you want to use for {{ $chatroom->name }}</h4>
                <label for="chatroom_name"></label> <label for="name"></label><input style="margin:0 auto; width:300px;" type="text" class="form-control" name="name" id="name" placeholder="Enter Name Here..." value="{{ $chatroom->name }}" required>
            </div>

            <div class="modal-footer">
                <button class="btn btn-md btn-default" data-dismiss="modal" >Cancel</button>
                <input class="btn btn-md btn-primary" type="submit">
            </div>
            </form>
        </div>
    </div>
</div>
{{--/Edit Chatroom--}}

{{--Delete Chatroom--}}
<div id="deleteChatroom-{{ $chatroom->id }}" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Delete Chatroom ({{ $chatroom->name }}) Permanently</h4>
            </div>

            <form class="form-horizontal" role="form" method="POST" action="{{ route('staff.rooms.destroy', ['id' => $chatroom->id]) }}">
            @csrf
            <div class="modal-body">
                <p>Are you sure about this ?</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-md btn-default" data-dismiss="modal">Cancel</button>
                <input class="btn btn-md btn-danger" type="submit">
            </div>
            </form>
        </div>
    </div>
</div>
{{--/Delete Chatroom--}}

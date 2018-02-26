@section('stylesheets')
<link rel="stylesheet" href="{{ url('files/wysibb/theme/default/wbbtheme.css') }}">
@endsection

<div class="col-md-10 col-sm-10 col-md-offset-1">
  <div class="clearfix visible-sm-block"></div>
    <div class="panel panel-chat shoutbox">
      <div class="panel-heading">
        <h4>{{ trans('blocks.chatbox') }}</h4>
      </div>
      <div class="chat-messages">
        <ul class="list-group">
          @foreach($shoutboxMessages as $message)
          @emojione($message)
          @endforeach
        </ul>
      </div>
      <div class="panel-footer ">
        <span class="badge-extra">{{ trans('common.type') }} <strong>:</strong> {{ trans('common.for') }} emoji</span> <span class="badge-extra">BBCode {{ trans('common.is-allowed') }}</span>
          <div class="form-group">
            <textarea class="form-control" id="chat-message"></textarea>
            <p id="chat-error" class="hidden text-danger"></p>
          </div>
      </div>
    </div>
  </div>
<br>

@section('javascripts')
<script type="text/javascript" src="{{ url('js/shout.js?v=05') }}"></script>
<script type="text/javascript" src="{{ url('files/wysibb/jquery.wysibb.js') }}"></script>
<script>
$(document).ready(function() {
  var wbbOpt = {
  }

  $("#chat-message").wysibb(wbbOpt);
  $(".wysibb-body").attr("onkeydown", "editorOnKeyDown(event, this);")
});
</script>
<script type="text/javascript">
function addTextToChat(text) {
	$( ".wysibb-text-editor" ).append( ' ' + text );
}
</script>
@endsection

<!-- Users -->
<div class="col-md-10 col-sm-10 col-md-offset-1">
  <div class="clearfix visible-sm-block"></div>
  <div class="panel panel-chat shoutbox">
    <div class="panel-heading">
      <h4>Newest Members</h4>
    </div>
    <ul>
      @foreach($users as $u)
      <a href="{{ route('profil', array('username' => $u->username, 'id' => $u->id)) }}">{{ $u->username }}</a> -
      @endforeach
    </ul>
  </div>
</div>
<!-- /Users -->

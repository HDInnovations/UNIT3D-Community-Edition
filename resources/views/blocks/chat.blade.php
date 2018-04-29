<chatbox :chatroom="{{ Auth::user()->chatroom()->first() }}" :user="{{ Auth::user() }}"></chatbox>

@section('javascripts')
<script src="{{ request()->getHost() }}:6001/socket.io/socket.io.js"></script>
@endsection
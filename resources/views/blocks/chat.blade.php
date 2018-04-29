<chatbox :chatroom="{{ auth()->user()->chatroom()->first() }}" :user="{{ auth()->user() }}"></chatbox>

@section('javascripts')
<script src="{{ request()->getHost() }}:6001/socket.io/socket.io.js"></script>
@endsection
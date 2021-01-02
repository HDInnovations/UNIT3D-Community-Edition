<chatbox :user="{{ App\Models\User::with(['chatStatus', 'chatroom', 'primaryRole'])->find(auth()->id()) }}"></chatbox>

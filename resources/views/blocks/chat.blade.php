<chatbox :user="{{ App\Models\User::with(['chatStatus', 'chatroom', 'group'])->find(auth()->id()) }}"></chatbox>

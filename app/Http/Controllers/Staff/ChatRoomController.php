<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreChatRoomRequest;
use App\Http\Requests\Staff\UpdateChatRoomRequest;
use App\Models\Chatroom;
use App\Models\User;
use App\Repositories\ChatRepository;
use Exception;

/**
 * @see \Tests\Feature\Http\Controllers\Staff\ChatRoomControllerTest
 */
class ChatRoomController extends Controller
{
    /**
     * ChatController Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Display All Chat Rooms.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.chat.room.index', [
            'chatrooms' => $this->chatRepository->rooms(),
        ]);
    }

    /**
     * Show Form For Creating A New Chatroom.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.chat.room.create');
    }

    /**
     * Store A New Chatroom.
     */
    public function store(StoreChatRoomRequest $request): \Illuminate\Http\RedirectResponse
    {
        Chatroom::create($request->validated());

        return to_route('staff.chatrooms.index')
            ->withSuccess('Chatroom Successfully Added');
    }

    /**
     * Chatroom Edit Form.
     */
    public function edit(Chatroom $chatroom): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.chat.room.edit', [
            'chatroom' => $chatroom,
        ]);
    }

    /**
     * Update A Chatroom.
     */
    public function update(UpdateChatRoomRequest $request, Chatroom $chatroom): \Illuminate\Http\RedirectResponse
    {
        $chatroom->update($request->validated());

        return to_route('staff.chatrooms.index')
            ->withSuccess('Chatroom Successfully Modified');
    }

    /**
     * Delete A Chatroom.
     *
     * @throws Exception
     */
    public function destroy(Chatroom $chatroom): \Illuminate\Http\RedirectResponse
    {
        $default = Chatroom::where('name', '=', config('chat.system_chatroom'))->sole()->id;

        User::whereBelongsTo($chatroom)->update(['chatroom_id' => $default]);

        $chatroom->delete();

        return to_route('staff.chatrooms.index')
            ->withSuccess('Chatroom Successfully Deleted');
    }
}

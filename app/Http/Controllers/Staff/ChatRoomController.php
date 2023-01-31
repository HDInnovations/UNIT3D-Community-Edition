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
        $chatrooms = $this->chatRepository->rooms();

        return \view('Staff.chat.room.index', [
            'chatrooms'    => $chatrooms,
        ]);
    }

    /**
     * Show Form For Creating A New Chatroom.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('Staff.chat.room.create');
    }

    /**
     * Store A New Chatroom.
     */
    public function store(StoreChatRoomRequest $request): \Illuminate\Http\RedirectResponse
    {
        Chatroom::create($request->validated());

        return \to_route('staff.rooms.index')
            ->withSuccess('Chatroom Successfully Added');
    }

    /**
     * Chatroom Edit Form.
     */
    public function edit(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $chatroom = Chatroom::findOrFail($id);

        return \view('Staff.chat.room.edit', ['chatroom' => $chatroom]);
    }

    /**
     * Update A Chatroom.
     */
    public function update(UpdateChatRoomRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        Chatroom::where('id', '=', $id)->update($request->validated());

        return \to_route('staff.rooms.index')
            ->withSuccess('Chatroom Successfully Modified');
    }

    /**
     * Delete A Chatroom.
     *
     * @throws \Exception
     */
    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        $chatroom = Chatroom::findOrFail($id);
        $users = User::where('chatroom_id', '=', $id)->get();
        $default = Chatroom::where('name', '=', \config('chat.system_chatroom'))->pluck('id');
        foreach ($users as $user) {
            $user->chatroom_id = $default[0];
            $user->save();
        }

        $chatroom->delete();

        return \to_route('staff.rooms.index')
            ->withSuccess('Chatroom Successfully Deleted');
    }
}

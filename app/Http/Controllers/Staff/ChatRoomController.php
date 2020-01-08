<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Models\Chatroom;
use App\Repositories\ChatRepository;
use Illuminate\Http\Request;

class ChatRoomController extends Controller
{
    /**
     * @var ChatRepository
     */
    private $chat;

    /**
     * ChatController Constructor.
     *
     * @param ChatRepository $chat
     */
    public function __construct(ChatRepository $chat)
    {
        $this->chat = $chat;
    }

    /**
     * Display All Chat Rooms.
     *
     * @return Factory|View
     */
    public function index()
    {
        $chatrooms = $this->chat->rooms();

        return view('Staff.chat.room.index', [
            'chatrooms'    => $chatrooms,
        ]);
    }

    /**
     * Store A New Chatroom.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function store(Request $request)
    {
        $chatroom = new Chatroom();
        $chatroom->name = $request->input('name');

        $v = validator($chatroom->toArray(), [
            'name' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff.rooms.index')
                ->withErrors($v->errors());
        }
        $chatroom->save();
        return redirect()->route('staff.rooms.index')
            ->withSuccess('Chatroom Successfully Added');
    }

    /**
     * Update A Chatroom.
     *
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function update(Request $request, $id)
    {
        $chatroom = Chatroom::findOrFail($id);
        $chatroom->name = $request->input('name');

        $v = validator($chatroom->toArray(), [
            'name' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff.rooms.index')
                ->withErrors($v->errors());
        }
        $chatroom->save();
        return redirect()->route('staff.rooms.index')
            ->withSuccess('Chatroom Successfully Modified');
    }

    /**
     * Delete A Chatroom.
     *
     * @param $id
     *
     * @return RedirectResponse
     */
    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        $chatroom = Chatroom::findOrFail($id);
        $chatroom->delete();

        return redirect()->route('staff.rooms.index')
            ->withSuccess('Chatroom Successfully Deleted');
    }
}

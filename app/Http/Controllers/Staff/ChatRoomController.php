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

use App\Http\Controllers\Controller;
use App\Models\Chatroom;
use App\Repositories\ChatRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

final class ChatRoomController extends Controller
{
    /**
     * @var ChatRepository
     */
    private ChatRepository $chat;
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private $viewFactory;
    /**
     * @var \Illuminate\Routing\Redirector
     */
    private $redirector;

    /**
     * ChatController Constructor.
     *
     * @param ChatRepository $chat
     */
    public function __construct(ChatRepository $chat, Factory $viewFactory, Redirector $redirector)
    {
        $this->chat = $chat;
        $this->viewFactory = $viewFactory;
        $this->redirector = $redirector;
    }

    /**
     * Display All Chat Rooms.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(): Factory
    {
        $chatrooms = $this->chat->rooms();

        return $this->viewFactory->make('Staff.chat.room.index', [
            'chatrooms'    => $chatrooms,
        ]);
    }

    /**
     * Store A New Chatroom.
     *
     * @param \Illuminate\Http\Request $request
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
            return $this->redirector->route('staff.rooms.index')
                ->withErrors($v->errors());
        } else {
            $chatroom->save();

            return $this->redirector->route('staff.rooms.index')
                ->withSuccess('Chatroom Successfully Added');
        }
    }

    /**
     * Update A Chatroom.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
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
            return $this->redirector->route('staff.rooms.index')
                ->withErrors($v->errors());
        } else {
            $chatroom->save();

            return $this->redirector->route('staff.rooms.index')
                ->withSuccess('Chatroom Successfully Modified');
        }
    }

    /**
     * Delete A Chatroom.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $chatroom = Chatroom::findOrFail($id);
        $chatroom->delete();

        return $this->redirector->route('staff.rooms.index')
            ->withSuccess('Chatroom Successfully Deleted');
    }
}

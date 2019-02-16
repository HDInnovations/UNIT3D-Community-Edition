<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use App\Message;
use App\Chatroom;
use App\ChatStatus;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use App\Events\MessageDeleted;
use App\Http\Controllers\Controller;
use App\Repositories\ChatRepository;

class ChatroomController extends Controller
{
    /**
     * @var ChatRepository
     */
    private $chat;

    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * ChatController Constructor.
     *
     * @param ChatRepository $chat
     * @param Toastr         $toastr
     */
    public function __construct(ChatRepository $chat, Toastr $toastr)
    {
        $this->chat = $chat;
        $this->toastr = $toastr;
    }

    /**
     * Chat Management.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $chatrooms = $this->chat->rooms();
        $chatstatuses = $this->chat->statuses();

        return view('Staff.chat.index', [
            'chatrooms'    => $chatrooms,
            'chatstatuses' => $chatstatuses,
        ]);
    }

    /**
     * Add A Chatroom.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $chatroom = new Chatroom();
        $chatroom->name = $request->input('name');

        $v = validator($chatroom->toArray(), [
            'name' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('chatManager')
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $chatroom->save();

            return redirect()->route('chatManager')
                ->with($this->toastr->success('Chatroom Successfully Added', 'Yay!', ['options']));
        }
    }

    /**
     * Edit A Chatroom.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $chatroom = Chatroom::findOrFail($id);
        $chatroom->name = $request->input('name');

        $v = validator($chatroom->toArray(), [
            'name' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('chatManager')
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $chatroom->save();

            return redirect()->route('chatManager')
                ->with($this->toastr->success('Chatroom Successfully Modified', 'Yay!', ['options']));
        }
    }

    /**
     * Delete A Chatroom.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $chatroom = Chatroom::findOrFail($id);
        $chatroom->delete();

        return redirect()->route('chatManager')
            ->with($this->toastr->success('Chatroom Successfully Deleted', 'Yay!', ['options']));
    }
}

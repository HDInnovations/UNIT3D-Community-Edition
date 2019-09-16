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

use App\Models\Message;
use App\Models\Chatroom;
use App\Models\ChatStatus;
use Illuminate\Http\Request;
use App\Events\MessageDeleted;
use App\Http\Controllers\Controller;
use App\Repositories\ChatRepository;

class ChatController extends Controller
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
    public function addChatroom(Request $request)
    {
        $chatroom = new Chatroom();
        $chatroom->name = $request->input('name');

        $v = validator($chatroom->toArray(), [
            'name' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('chatManager')
                ->withErrors($v->errors());
        } else {
            $chatroom->save();

            return redirect()->route('chatManager')
                ->withSuccess('Chatroom Successfully Added');
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
    public function editChatroom(Request $request, $id)
    {
        $chatroom = Chatroom::findOrFail($id);
        $chatroom->name = $request->input('name');

        $v = validator($chatroom->toArray(), [
            'name' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('chatManager')
                ->withErrors($v->errors());
        } else {
            $chatroom->save();

            return redirect()->route('chatManager')
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
    public function deleteChatroom($id)
    {
        $chatroom = Chatroom::findOrFail($id);
        $chatroom->delete();

        return redirect()->route('chatManager')
            ->withSuccess('Chatroom Successfully Deleted');
    }

    /**
     * Add A Chat Status.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function addChatStatus(Request $request)
    {
        $chatstatus = new ChatStatus();
        $chatstatus->name = $request->input('name');
        $chatstatus->color = $request->input('color');
        $chatstatus->icon = $request->input('icon');

        $v = validator($chatstatus->toArray(), [
            'name'  => 'required',
            'color' => 'required',
            'icon'  => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('chatManager')
                ->withErrors($v->errors());
        } else {
            $chatstatus->save();

            return redirect()->route('chatManager')
                ->withSuccess('Chat Status Successfully Added');
        }
    }

    /**
     * Edit A Chat Status.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function editChatStatus(Request $request, $id)
    {
        $chatstatus = ChatStatus::findOrFail($id);
        $chatstatus->name = $request->input('name');
        $chatstatus->color = $request->input('color');
        $chatstatus->icon = $request->input('icon');

        $v = validator($chatstatus->toArray(), [
            'name'  => 'required',
            'color' => 'required',
            'icon'  => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('chatManager')
                ->withErrors($v->errors());
        } else {
            $chatstatus->save();

            return redirect()->route('chatManager')
                ->withSuccess('Chat Status Successfully Modified');
        }
    }

    /**
     * Delete A Chat Status.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function deleteChatStatus($id)
    {
        $chatstatus = ChatStatus::findOrFail($id);
        $chatstatus->delete();

        return redirect()->route('chatManager')
            ->withSuccess('Chat Status Successfully Deleted');
    }

    /**
     * Flush Chat Messages.
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function flushChat()
    {
        foreach (Message::all() as $message) {
            broadcast(new MessageDeleted($message));
            $message->delete();
        }

        $this->chat->systemMessage(
            'Chatbox Has Been Flushed! :broom:'
        );

        return redirect()->to('staff_dashboard')
            ->withSuccess('Chatbox Has Been Flushed');
    }
}

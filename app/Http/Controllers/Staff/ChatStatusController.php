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

use App\ChatStatus;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ChatRepository;

class ChatStatusController extends Controller
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
     * Add A Chat Status.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
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
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $chatstatus->save();

            return redirect()->route('chatManager')
                ->with($this->toastr->success('Chat Status Successfully Added', 'Yay!', ['options']));
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
    public function update(Request $request, $id)
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
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $chatstatus->save();

            return redirect()->route('chatManager')
                ->with($this->toastr->success('Chat Status Successfully Modified', 'Yay!', ['options']));
        }
    }

    /**
     * Delete A Chat Status.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $chatstatus = ChatStatus::findOrFail($id);
        $chatstatus->delete();

        return redirect()->route('chatManager')
            ->with($this->toastr->success('Chat Status Successfully Deleted', 'Yay!', ['options']));
    }
}

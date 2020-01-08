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
use App\Models\ChatStatus;
use App\Repositories\ChatRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ChatStatusController extends Controller
{
    private \App\Repositories\ChatRepository $chat;

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
     * @return Factory|View
     */
    public function index()
    {
        $chatstatuses = $this->chat->statuses();

        return view('Staff.chat.status.index', [
            'chatstatuses' => $chatstatuses,
        ]);
    }

    /**
     * Store A New Chat Status.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
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
            return redirect()->route('staff.statuses.index')
                ->withErrors($v->errors());
        }
        $chatstatus->save();

        return redirect()->route('staff.statuses.index')
            ->withSuccess('Chat Status Successfully Added');
    }

    /**
     * Update A Chat Status.
     *
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
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
            return redirect()->route('staff.statuses.index')
                ->withErrors($v->errors());
        }
        $chatstatus->save();

        return redirect()->route('staff.statuses.index')
            ->withSuccess('Chat Status Successfully Modified');
    }

    /**
     * Delete A Chat Status.
     *
     * @param $id
     *
     * @return RedirectResponse
     */
    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        $chatstatus = ChatStatus::findOrFail($id);
        $chatstatus->delete();

        return redirect()->route('staff.statuses.index')
            ->withSuccess('Chat Status Successfully Deleted');
    }
}

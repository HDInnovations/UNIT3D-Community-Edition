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
use Illuminate\Routing\Redirector;

final class ChatStatusController extends Controller
{
    /**
     * @var ChatRepository
     */
    private ChatRepository $chat;
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private Factory $viewFactory;
    /**
     * @var \Illuminate\Routing\Redirector
     */
    private Redirector $redirector;

    /**
     * ChatController Constructor.
     *
     * @param  ChatRepository  $chat
     * @param  \Illuminate\Contracts\View\Factory  $viewFactory
     * @param  \Illuminate\Routing\Redirector  $redirector
     */
    public function __construct(ChatRepository $chat, Factory $viewFactory, Redirector $redirector)
    {
        $this->chat = $chat;
        $this->viewFactory = $viewFactory;
        $this->redirector = $redirector;
    }

    /**
     * Chat Management.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(): Factory
    {
        $chatstatuses = $this->chat->statuses();

        return $this->viewFactory->make('Staff.chat.status.index', [
            'chatstatuses' => $chatstatuses,
        ]);
    }

    /**
     * Store A New Chat Status.
     *
     * @param \Illuminate\Http\Request $request
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
            return $this->redirector->route('staff.statuses.index')
                ->withErrors($v->errors());
        } else {
            $chatstatus->save();

            return $this->redirector->route('staff.statuses.index')
                ->withSuccess('Chat Status Successfully Added');
        }
    }

    /**
     * Update A Chat Status.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
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
            return $this->redirector->route('staff.statuses.index')
                ->withErrors($v->errors());
        } else {
            $chatstatus->save();

            return $this->redirector->route('staff.statuses.index')
                ->withSuccess('Chat Status Successfully Modified');
        }
    }

    /**
     * Delete A Chat Status.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $chatstatus = ChatStatus::findOrFail($id);
        $chatstatus->delete();

        return $this->redirector->route('staff.statuses.index')
            ->withSuccess('Chat Status Successfully Deleted');
    }
}

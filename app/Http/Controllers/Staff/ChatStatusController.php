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
use App\Models\ChatStatus;
use App\Repositories\ChatRepository;
use Illuminate\Http\Request;

/**
 * @see \Tests\Feature\Http\Controllers\Staff\ChatStatusControllerTest
 */
class ChatStatusController extends Controller
{
    /**
     * ChatController Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Chat Management.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $chatstatuses = $this->chatRepository->statuses();

        return \view('Staff.chat.status.index', [
            'chatstatuses' => $chatstatuses,
        ]);
    }

    /**
     * Show Form For Creating A New Chat Status.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('Staff.chat.status.create');
    }

    /**
     * Store A New Chat Status.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $chatStatus = new ChatStatus();
        $chatStatus->name = $request->input('name');
        $chatStatus->color = $request->input('color');
        $chatStatus->icon = $request->input('icon');

        $v = \validator($chatStatus->toArray(), [
            'name'  => 'required|unique:App\Models\ChatStatus,name',
            'color' => 'required',
            'icon'  => 'required',
        ]);

        if ($v->fails()) {
            return \to_route('staff.chat-statuses.index')
                ->withErrors($v->errors());
        }

        $chatStatus->save();

        return \to_route('staff.chat-statuses.index')
            ->withSuccess('Chat Status Successfully Added');
    }

    /**
     * Chat Status Edit Form.
     */
    public function edit(ChatStatus $chatStatus): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('Staff.chat.status.edit', ['chatstatus' => $chatStatus]);
    }

    /**
     * Update A Chat Status.
     */
    public function update(Request $request, ChatStatus $chatStatus): \Illuminate\Http\RedirectResponse
    {
        $chatStatus->name = $request->input('name');
        $chatStatus->color = $request->input('color');
        $chatStatus->icon = $request->input('icon');

        $v = \validator($chatStatus->toArray(), [
            'name'  => 'required|unique:App\Models\ChatStatus,name',
            'color' => 'required',
            'icon'  => 'required',
        ]);

        if ($v->fails()) {
            return \to_route('staff.chat-statuses.index')
                ->withErrors($v->errors());
        }

        $chatStatus->save();

        return \to_route('staff.chat-statuses.index')
            ->withSuccess('Chat Status Successfully Modified');
    }

    /**
     * Delete A Chat Status.
     *
     * @throws \Exception
     */
    public function destroy(ChatStatus $chatStatus): \Illuminate\Http\RedirectResponse
    {
        $chatStatus->delete();

        return \to_route('staff.chat-statuses.index')
            ->withSuccess('Chat Status Successfully Deleted');
    }
}

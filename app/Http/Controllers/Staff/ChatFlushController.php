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
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use App\Events\MessageDeleted;
use App\Http\Controllers\Controller;
use App\Repositories\ChatRepository;

class ChatFlushController extends Controller
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
     * ChatFlushController Constructor.
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
     * Flush Chat Messages.
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        foreach (Message::all() as $message) {
            broadcast(new MessageDeleted($message));
            $message->delete();
        }

        $this->chat->systemMessage(
            'Chatbox Has Been Flushed! :broom:'
        );

        return redirect('staff.dashboard.index')
            ->with($this->toastr->success('Chatbox Has Been Flushed', 'Yay!', ['options']));
    }
}

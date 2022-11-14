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

use App\Helpers\TorrentHelper;
use App\Http\Controllers\Controller;
use App\Models\PrivateMessage;
use App\Models\Torrent;
use App\Repositories\ChatRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\Staff\ModerationControllerTest
 */
class ModerationController extends Controller
{
    /**
     * ModerationController Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Torrent Moderation Panel.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $current = Carbon::now();
        $pending = Torrent::with(['user', 'category', 'type'])->pending()->get();
        $postponed = Torrent::with(['user', 'category', 'type'])->postponed()->get();
        $rejected = Torrent::with(['user', 'category', 'type'])->rejected()->get();

        return \view('Staff.moderation.index', [
            'current'   => $current,
            'pending'   => $pending,
            'postponed' => $postponed,
            'rejected'  => $rejected,
        ]);
    }

    /**
     * Update a torrent's moderation status.
     */
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $torrent = Torrent::withAnyStatus()->with('user')->findOrFail($id);

        if ((int) $request->old_status !== $torrent->status) {
            return \to_route('torrent', ['id' => $id])
                ->withInput()
                ->withErrors('Torrent has already been moderated since this page was loaded.');
        }

        if ((int) $request->status === $torrent->status) {
            return \to_route('torrent', ['id' => $id])
                ->withInput()
                ->withErrors(
                    match ($torrent->status) {
                        0       => 'Torrent already pending.',
                        1       => 'Torrent already approved.',
                        2       => 'Torrent already rejected.',
                        3       => 'Torrent already postponed.',
                        default => 'Invalid moderation status.'
                    }
                );
        }

        $user = \auth()->user();

        switch ($request->status) {
            case 1: // Approve
                $appurl = \config('app.url');

                // Announce To Shoutbox
                if ($torrent->anon === 0) {
                    $this->chatRepository->systemMessage(
                        \sprintf('User [url=%s/users/', $appurl).$torrent->user->username.']'.$torrent->user->username.\sprintf('[/url] has uploaded a new '.$torrent->category->name.'. [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url], grab it now! :slight_smile:'
                    );
                } else {
                    $this->chatRepository->systemMessage(
                        \sprintf('An anonymous user has uploaded a new '.$torrent->category->name.'. [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url], grab it now! :slight_smile:'
                    );
                }

                TorrentHelper::approveHelper($torrent->id);

                return \to_route('staff.moderation.index')
                    ->withSuccess('Torrent Approved');

            case 2: // Reject
                $v = \validator($request->all(), [
                    'id'      => 'required|exists:torrents',
                    'slug'    => 'required|exists:torrents',
                    'message' => 'required',
                ]);

                if ($v->fails()) {
                    return \to_route('staff.moderation.index')
                        ->withErrors($v->errors());
                }

                $torrent->markRejected();

                $privateMessage = new PrivateMessage();
                $privateMessage->sender_id = $user->id;
                $privateMessage->receiver_id = $torrent->user_id;
                $privateMessage->subject = \sprintf('Your upload, %s ,has been rejected by %s', $torrent->name, $user->username);
                $privateMessage->message = \sprintf("Greetings, \n\nYour upload %s has been rejected. Please see below the message from the staff member.\n\n%s", $torrent->name, $request->message);
                $privateMessage->save();

                return \to_route('staff.moderation.index')
                    ->withSuccess('Torrent Rejected');

            case 3: // Postpone
                $v = \validator($request->all(), [
                    'id'      => 'required|exists:torrents',
                    'slug'    => 'required|exists:torrents',
                    'message' => 'required',
                ]);

                if ($v->fails()) {
                    return \to_route('staff.moderation.index')
                        ->withErrors($v->errors());
                }

                $torrent->markPostponed();

                $privateMessage = new PrivateMessage();
                $privateMessage->sender_id = $user->id;
                $privateMessage->receiver_id = $torrent->user_id;
                $privateMessage->subject = \sprintf('Your upload, %s ,has been postponed by %s', $torrent->name, $user->username);
                $privateMessage->message = \sprintf("Greetings, \n\nYour upload, %s ,has been postponed. Please see below the message from the staff member.\n\n%s", $torrent->name, $request->message);
                $privateMessage->save();

                return \to_route('staff.moderation.index')
                    ->withSuccess('Torrent Postponed');

            default: // Undefined status
                return \to_route('torrent', ['id' => $id])
                    ->withErrors('Invalid moderation status.');
        }
    }
}

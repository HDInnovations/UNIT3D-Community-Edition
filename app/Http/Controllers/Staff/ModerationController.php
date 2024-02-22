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
use App\Http\Requests\Staff\UpdateModerationRequest;
use App\Models\PrivateMessage;
use App\Models\Scopes\ApprovedScope;
use App\Models\Torrent;
use App\Repositories\ChatRepository;
use App\Services\Unit3dAnnounce;

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
        return view('Staff.moderation.index', [
            'current' => now(),
            'pending' => Torrent::withoutGlobalScope(ApprovedScope::class)
                ->with(['user.group', 'category', 'type', 'resolution', 'category'])
                ->where('status', '=', Torrent::PENDING)
                ->get(),
            'postponed' => Torrent::withoutGlobalScope(ApprovedScope::class)
                ->with(['user.group', 'moderated.group', 'category', 'type', 'resolution', 'category'])
                ->where('status', '=', Torrent::POSTPONED)
                ->get(),
            'rejected' => Torrent::withoutGlobalScope(ApprovedScope::class)
                ->with(['user.group', 'moderated.group', 'category', 'type', 'resolution', 'category'])
                ->where('status', '=', Torrent::REJECTED)
                ->get(),
        ]);
    }

    /**
     * Update a torrent's moderation status.
     */
    public function update(UpdateModerationRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $torrent = Torrent::withoutGlobalScope(ApprovedScope::class)->with('user')->findOrFail($id);

        if ($request->integer('old_status') !== $torrent->status) {
            return to_route('torrents.show', ['id' => $id])
                ->withInput()
                ->withErrors('Torrent has already been moderated since this page was loaded.');
        }

        if ($request->integer('status') === $torrent->status) {
            return to_route('torrents.show', ['id' => $id])
                ->withInput()
                ->withErrors(
                    match ($torrent->status) {
                        Torrent::PENDING   => 'Torrent already pending.',
                        Torrent::APPROVED  => 'Torrent already approved.',
                        Torrent::REJECTED  => 'Torrent already rejected.',
                        Torrent::POSTPONED => 'Torrent already postponed.',
                        default            => 'Invalid moderation status.'
                    }
                );
        }

        $staff = auth()->user();

        switch ($request->status) {
            case Torrent::APPROVED:
                // Announce To Shoutbox
                if ($torrent->anon === 0) {
                    $this->chatRepository->systemMessage(
                        sprintf('User [url=%s/users/', config('app.url')).$torrent->user->username.']'.$torrent->user->username.sprintf('[/url] has uploaded a new '.$torrent->category->name.'. [url=%s/torrents/', config('app.url')).$id.']'.$torrent->name.'[/url], grab it now!'
                    );
                } else {
                    $this->chatRepository->systemMessage(
                        sprintf('An anonymous user has uploaded a new '.$torrent->category->name.'. [url=%s/torrents/', config('app.url')).$id.']'.$torrent->name.'[/url], grab it now!'
                    );
                }

                TorrentHelper::approveHelper($id);

                return to_route('staff.moderation.index')
                    ->withSuccess('Torrent Approved');

            case Torrent::REJECTED:
                $torrent->update([
                    'status'       => Torrent::REJECTED,
                    'moderated_at' => now(),
                    'moderated_by' => $staff->id,
                ]);

                PrivateMessage::create([
                    'sender_id'   => $staff->id,
                    'receiver_id' => $torrent->user_id,
                    'subject'     => 'Your upload, '.$torrent->name.' ,has been rejected by '.$staff->username,
                    'message'     => "Greetings, \n\nYour upload ".$torrent->name." has been rejected. Please see below the message from the staff member.\n\n".$request->message,
                ]);

                cache()->forget('announce-torrents:by-infohash:'.$torrent->info_hash);

                Unit3dAnnounce::addTorrent($torrent);

                return to_route('staff.moderation.index')
                    ->withSuccess('Torrent Rejected');

            case Torrent::POSTPONED:
                $torrent->update([
                    'status'       => Torrent::POSTPONED,
                    'moderated_at' => now(),
                    'moderated_by' => $staff->id,
                ]);

                PrivateMessage::create([
                    'sender_id'   => $staff->id,
                    'receiver_id' => $torrent->user_id,
                    'subject'     => 'Your upload, '.$torrent->name.' ,has been postponed by '.$staff->username,
                    'message'     => "Greetings, \n\nYour upload, ".$torrent->name." ,has been postponed. Please see below the message from the staff member.\n\n".$request->message,
                ]);

                cache()->forget('announce-torrents:by-infohash:'.$torrent->info_hash);

                Unit3dAnnounce::addTorrent($torrent);

                return to_route('staff.moderation.index')
                    ->withSuccess('Torrent Postponed');

            default: // Undefined status
                return to_route('torrents.show', ['id' => $id])
                    ->withErrors('Invalid moderation status.');
        }
    }
}

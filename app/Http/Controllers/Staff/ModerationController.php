<?php

declare(strict_types=1);

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
use App\Models\Conversation;
use App\Models\PrivateMessage;
use App\Models\Scopes\ApprovedScope;
use App\Models\Torrent;
use App\Models\TorrentModerationMessage;
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
        abort_unless(auth()->user()->group->is_torrent_modo, 403);

        return view('Staff.moderation.index', [
            'current' => now(),
            'pending' => Torrent::withoutGlobalScope(ApprovedScope::class)
                ->with(['user.group', 'category', 'type', 'resolution'])
                ->where('status', '=', Torrent::PENDING)
                ->get(),
            'postponed' => Torrent::withoutGlobalScope(ApprovedScope::class)
                ->with(['user.group', 'category', 'type', 'resolution'])
                ->where('status', '=', Torrent::POSTPONED)
                ->get(),
            'rejected' => Torrent::withoutGlobalScope(ApprovedScope::class)
                ->with(['user.group', 'category', 'type', 'resolution'])
                ->where('status', '=', Torrent::REJECTED)
                ->get(),
        ]);
    }

    /**
     * Update a torrent's moderation status.
     */
    public function update(UpdateModerationRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        abort_unless(auth()->user()->group->is_torrent_modo, 403);

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
                        \sprintf('User [url=%s/users/', config('app.url')).$torrent->user->username.']'.$torrent->user->username.\sprintf('[/url] has uploaded a new '.$torrent->category->name.'. [url=%s/torrents/', config('app.url')).$id.']'.$torrent->name.'[/url], grab it now!'
                    );
                } else {
                    $this->chatRepository->systemMessage(
                        \sprintf('An anonymous user has uploaded a new '.$torrent->category->name.'. [url=%s/torrents/', config('app.url')).$id.']'.$torrent->name.'[/url], grab it now!'
                    );
                }

                TorrentHelper::approveHelper($id);

                TorrentModerationMessage::create([
                    'moderated_by' => $staff->id,
                    'torrent_id'   => $torrent->id,
                    'status'       => Torrent::APPROVED,
                ]);

                return to_route('staff.moderation.index')
                    ->with('success', 'Torrent Approved');

            case Torrent::REJECTED:
                $torrent->update([
                    'status' => Torrent::REJECTED,
                ]);

                TorrentModerationMessage::create([
                    'moderated_by' => $staff->id,
                    'torrent_id'   => $torrent->id,
                    'status'       => Torrent::REJECTED,
                    'message'      => $request->message,
                ]);

                $conversation = Conversation::create(['subject' => 'Your upload, '.$torrent->name.', has been rejected by '.$staff->username]);

                $conversation->users()->sync([$staff->id => ['read' => true], $torrent->user_id]);

                PrivateMessage::create([
                    'conversation_id' => $conversation->id,
                    'sender_id'       => $staff->id,
                    'message'         => "Greetings, \n\nYour upload, [url=/torrents/".$id.']'.$torrent->name."[/url], has been rejected. Please see below the message from the staff member.\n\n[quote=".$staff->username.']'.$request->message.'[/quote]',
                ]);

                cache()->forget('announce-torrents:by-infohash:'.$torrent->info_hash);

                Unit3dAnnounce::addTorrent($torrent);

                return to_route('staff.moderation.index')
                    ->with('success', 'Torrent Rejected');

            case Torrent::POSTPONED:
                $torrent->update([
                    'status' => Torrent::POSTPONED,
                ]);

                TorrentModerationMessage::create([
                    'moderated_by' => $staff->id,
                    'torrent_id'   => $torrent->id,
                    'status'       => Torrent::POSTPONED,
                    'message'      => $request->message,
                ]);

                $conversation = Conversation::create(['subject' => 'Your upload, '.$torrent->name.', has been postponed by '.$staff->username]);

                $conversation->users()->sync([$staff->id => ['read' => true], $torrent->user_id]);

                PrivateMessage::create([
                    'conversation_id' => $conversation->id,
                    'sender_id'       => $staff->id,
                    'message'         => "Greetings, \n\nYour upload, [url=/torrents/".$id.']'.$torrent->name."[/url], has been postponed. Please see below the message from the staff member.\n\n[quote=".$staff->username.']'.$request->message.'[/quote]',
                ]);

                cache()->forget('announce-torrents:by-infohash:'.$torrent->info_hash);

                Unit3dAnnounce::addTorrent($torrent);

                return to_route('staff.moderation.index')
                    ->with('success', 'Torrent Postponed');

            default: // Undefined status
                return to_route('torrents.show', ['id' => $id])
                    ->withErrors('Invalid moderation status.');
        }
    }
}

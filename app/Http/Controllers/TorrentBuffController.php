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

namespace App\Http\Controllers;

use App\Bots\IRCAnnounceBot;
use App\Models\FeaturedTorrent;
use App\Models\FreeleechToken;
use App\Models\Torrent;
use App\Repositories\ChatRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\TorrentControllerTest
 */
class TorrentBuffController extends Controller
{
    /**
     * TorrentController Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Bump A Torrent.
     */
    public function bumpTorrent(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        \abort_unless($user->group->is_modo || $user->group->is_internal, 403);
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $torrent->bumped_at = Carbon::now();
        $torrent->save();

        // Announce To Chat
        $torrentUrl = \href_torrent($torrent);
        $profileUrl = \href_profile($user);

        $this->chatRepository->systemMessage(
            \sprintf('Attention, [url=%s]%s[/url] has been bumped to the top by [url=%s]%s[/url]! It could use more seeds!', $torrentUrl, $torrent->name, $profileUrl, $user->username)
        );

        // Announce To IRC
        if (\config('irc-bot.enabled')) {
            $appname = \config('app.name');
            $ircAnnounceBot = new IRCAnnounceBot();
            $ircAnnounceBot->message(\config('irc-bot.channel'), '['.$appname.'] User '.$user->username.' has bumped '.$torrent->name.' , it could use more seeds!');
            $ircAnnounceBot->message(\config('irc-bot.channel'), '[Category: '.$torrent->category->name.'] [Type: '.$torrent->type->name.'] [Size:'.$torrent->getSize().']');
            $ircAnnounceBot->message(\config('irc-bot.channel'), \sprintf('[Link: %s]', $torrentUrl));
        }

        return \to_route('torrent', ['id' => $torrent->id])
            ->withSuccess('Torrent Has Been Bumped To The Top Successfully!');
    }

    /**
     * Sticky A Torrent.
     */
    public function sticky(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        \abort_unless($user->group->is_modo || $user->group->is_internal, 403);
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $torrent->sticky = $torrent->sticky == 0 ? '1' : '0';
        $torrent->save();

        return \to_route('torrent', ['id' => $torrent->id])
            ->withSuccess('Torrent Sticky Status Has Been Adjusted!');
    }

    /**
     * Freeleech A Torrent (1% to 100% Free).
     */
    public function grantFL(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        \abort_unless($user->group->is_modo || $user->group->is_internal, 403);
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $torrentUrl = \href_torrent($torrent);
        $torrentFlAmount = $request->input('freeleech');

        $v = \validator($request->input(), [
            'freeleech' => 'numeric|not_in:0',
        ]);

        if ($v->fails()) {
            return \to_route('torrent', ['id' => $torrent->id])
                ->withErrors($v->errors());
        }

        if ($torrent->free == 0) {
            $torrent->free = $torrentFlAmount;
            $fl_until = $request->input('fl_until');
            if ($fl_until !== null) {
                $torrent->fl_until = Carbon::now()->addDays($request->input('fl_until'));
                $this->chatRepository->systemMessage(
                    \sprintf('Ladies and Gents, [url=%s]%s[/url] has been granted %s%% FreeLeech for '.$request->input('fl_until').' days. :stopwatch:', $torrentUrl, $torrent->name, $torrentFlAmount)
                );
            } else {
                $this->chatRepository->systemMessage(
                    \sprintf('Ladies and Gents, [url=%s]%s[/url] has been granted %s%% FreeLeech! Grab It While You Can! :fire:', $torrentUrl, $torrent->name, $torrentFlAmount)
                );
            }
        } else {
            // Get amount of FL before revoking for chat announcement
            $torrentFlAmount = $torrent->free;
            $torrent->free = '0';

            $this->chatRepository->systemMessage(
                \sprintf('Ladies and Gents, [url=%s]%s[/url] has been revoked of its %s%% FreeLeech! :poop:', $torrentUrl, $torrent->name, $torrentFlAmount)
            );
        }

        $torrent->save();

        return \to_route('torrent', ['id' => $torrent->id])
            ->withSuccess('Torrent FL Has Been Adjusted!');
    }

    /**
     * Feature A Torrent.
     */
    public function grantFeatured(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        \abort_unless($user->group->is_modo || $user->group->is_internal, 403);
        $torrent = Torrent::withAnyStatus()->findOrFail($id);

        if ($torrent->featured == 0) {
            $torrent->free = '100';
            $torrent->doubleup = '1';
            $torrent->featured = '1';
            $torrent->save();

            $featured = new FeaturedTorrent();
            $featured->user_id = $user->id;
            $featured->torrent_id = $torrent->id;
            $featured->save();

            $torrentUrl = \href_torrent($torrent);
            $profileUrl = \href_profile($user);
            $this->chatRepository->systemMessage(
                \sprintf('Ladies and Gents, [url=%s]%s[/url] has been added to the Featured Torrents Slider by [url=%s]%s[/url]! Grab It While You Can! :fire:', $torrentUrl, $torrent->name, $profileUrl, $user->username)
            );

            return \to_route('torrent', ['id' => $torrent->id])
                ->withSuccess('Torrent Is Now Featured!');
        }

        return \to_route('torrent', ['id' => $torrent->id])
            ->withErrors('Torrent Is Already Featured!');
    }

    /**
     * UnFeature A Torrent.
     */
    public function revokeFeatured(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        \abort_unless($user->group->is_modo, 403);

        $featured_torrent = FeaturedTorrent::where('torrent_id', '=', $id)->firstOrFail();

        $torrent = Torrent::withAnyStatus()->findOrFail($id);

        if (isset($torrent)) {
            $torrent->free = '0';
            $torrent->doubleup = '0';
            $torrent->featured = '0';
            $torrent->save();

            $appurl = \config('app.url');

            $this->chatRepository->systemMessage(
                \sprintf('Ladies and Gents, [url=%s/torrents/%s]%s[/url] is no longer featured. :poop:', $appurl, $torrent->id, $torrent->name)
            );
        }

        $featured_torrent->delete();

        return \to_route('torrent', ['id' => $torrent->id])
            ->withSuccess('Revoked featured from Torrent!');
    }

    /**
     * Double Upload A Torrent.
     */
    public function grantDoubleUp(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        \abort_unless($user->group->is_modo || $user->group->is_internal, 403);
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $torrentUrl = \href_torrent($torrent);

        if ($torrent->doubleup == 0) {
            $torrent->doubleup = '1';
            $du_until = $request->input('du_until');
            if ($du_until !== null) {
                $torrent->du_until = Carbon::now()->addDays($request->input('du_until'));
                $this->chatRepository->systemMessage(
                    \sprintf('Ladies and Gents, [url=%s]%s[/url] has been granted Double Upload for '.$request->input('du_until').' days. :stopwatch:', $torrentUrl, $torrent->name)
                );
            } else {
                $this->chatRepository->systemMessage(
                    \sprintf('Ladies and Gents, [url=%s]%s[/url] has been granted Double Upload! Grab It While You Can! :fire:', $torrentUrl, $torrent->name)
                );
            }
        } else {
            $torrent->doubleup = '0';
            $this->chatRepository->systemMessage(
                \sprintf('Ladies and Gents, [url=%s]%s[/url] has been revoked of its Double Upload! :poop:', $torrentUrl, $torrent->name)
            );
        }

        $torrent->save();

        return \to_route('torrent', ['id' => $torrent->id])
            ->withSuccess('Torrent DoubleUpload Has Been Adjusted!');
    }

    /**
     * Use Freeleech Token On A Torrent.
     */
    public function freeleechToken(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $torrent = Torrent::withAnyStatus()->findOrFail($id);

        $activeToken = \cache()->rememberForever(
            'freeleech_token:'.$user->id.':'.$torrent->id,
            fn () => $user->freeleechTokens()->where('torrent_id', '=', $torrent->id)->exists()
        );

        if ($user->fl_tokens >= 1 && ! $activeToken) {
            $freeleechToken = new FreeleechToken();
            $freeleechToken->user_id = $user->id;
            $freeleechToken->torrent_id = $torrent->id;
            $freeleechToken->save();

            $user->fl_tokens -= '1';
            $user->save();

            return \to_route('torrent', ['id' => $torrent->id])
                ->withSuccess('You Have Successfully Activated A Freeleech Token For This Torrent!');
        }

        return \to_route('torrent', ['id' => $torrent->id])
            ->withErrors('You Dont Have Enough Freeleech Tokens Or Already Have One Activated On This Torrent.');
    }
}

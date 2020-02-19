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

use App\Models\BonExchange;
use App\Models\BonTransactions;
use App\Models\PersonalFreeleech;
use App\Models\Post;
use App\Models\PrivateMessage;
use App\Models\Torrent;
use App\Models\User;
use App\Notifications\NewBon;
use App\Notifications\NewPostTip;
use App\Notifications\NewUploadTip;
use App\Repositories\ChatRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BonusController extends Controller
{
    /**
     * @var ChatRepository
     */
    private $chat;

    /**
     * BonusController Constructor.
     *
     * @param ChatRepository $chat
     */
    public function __construct(ChatRepository $chat)
    {
        $this->chat = $chat;
    }

    /**
     * Show Bonus Gifts System.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function gifts(Request $request)
    {
        $user = $request->user();
        $userbon = $user->getSeedbonus();
        $gifttransactions = BonTransactions::with(['senderObj', 'receiverObj'])->where(function ($query) use ($user) {
            $query->where('sender', '=', $user->id)->orwhere('receiver', '=', $user->id);
        })->where('name', '=', 'gift')->orderBy('date_actioned', 'DESC')->paginate(25);

        $gifts_sent = BonTransactions::where('sender', '=', $user->id)->where('name', '=', 'gift')->sum('cost');
        $gifts_received = BonTransactions::where('receiver', '=', $user->id)->where('name', '=', 'gift')->sum('cost');

        return view('bonus.gifts', [
            'user'              => $user,
            'gifttransactions'  => $gifttransactions,
            'userbon'           => $userbon,
            'gifts_sent'        => $gifts_sent,
            'gifts_received'    => $gifts_received,
        ]);
    }

    /**
     * Show Bonus Tips System.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tips(Request $request)
    {
        $user = $request->user();
        $userbon = $user->getSeedbonus();
        $bontransactions = BonTransactions::with(['senderObj', 'receiverObj'])->where(function ($query) use ($user) {
            $query->where('sender', '=', $user->id)->orwhere('receiver', '=', $user->id);
        })->where('name', '=', 'tip')->orderBy('date_actioned', 'DESC')->paginate(25);

        $tips_sent = BonTransactions::where('sender', '=', $user->id)->where('name', '=', 'tip')->sum('cost');
        $tips_received = BonTransactions::where('receiver', '=', $user->id)->where('name', '=', 'tip')->sum('cost');

        return view('bonus.tips', [
            'user'              => $user,
            'bontransactions'   => $bontransactions,
            'userbon'           => $userbon,
            'tips_sent'         => $tips_sent,
            'tips_received'     => $tips_received,
        ]);
    }

    /**
     * Show Bonus Store System.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $users = User::oldest('username')->get();
        $userbon = $user->getSeedbonus();
        $activefl = PersonalFreeleech::where('user_id', '=', $user->id)->first();
        $BonExchange = new BonExchange();
        $bontransactions = BonTransactions::with('exchange')->where('sender', '=', $user->id)->where('itemID', '>', 0)->orderBy('date_actioned', 'DESC')->limit(25)->get();
        $uploadOptions = $BonExchange->getUploadOptions();
        $downloadOptions = $BonExchange->getDownloadOptions();
        $personalFreeleech = $BonExchange->getPersonalFreeleechOption();
        $invite = $BonExchange->getInviteOption();

        return view('bonus.store', [
            'users'             => $users,
            'userbon'           => $userbon,
            'activefl'          => $activefl,
            'bontransactions'   => $bontransactions,
            'uploadOptions'     => $uploadOptions,
            'downloadOptions'   => $downloadOptions,
            'personalFreeleech' => $personalFreeleech,
            'invite'            => $invite,
        ]);
    }

    /**
     * Show Bonus Gift System.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function gift(Request $request)
    {
        $user = $request->user();
        $userbon = $user->getSeedbonus();

        return view('bonus.gift', [
            'userbon'           => $userbon,
        ]);
    }

    /**
     * Show Bonus Earnings System.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $username
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bonus(Request $request, $username = '')
    {
        $user = $request->user();
        $userbon = $user->getSeedbonus();

        //Dying Torrent
        $dying = $this->getDyingCount($request);
        //Legendary Torrents
        $legendary = $this->getLegendaryCount($request);
        //Old Torrents
        $old = $this->getOldCount($request);
        //Large Torrents
        $huge = $this->getHugeCount($request);
        //Large Torrents
        $large = $this->getLargeCount($request);
        //Everyday Torrents
        $regular = $this->getRegularCount($request);

        //Participant Seeder
        $participant = $this->getParticipaintSeedCount($request);
        //TeamPlayer Seeder
        $teamplayer = $this->getTeamPlayerSeedCount($request);
        //Committed Seeder
        $committed = $this->getCommitedSeedCount($request);
        //MVP Seeder
        $mvp = $this->getMVPSeedCount($request);
        //Legend Seeder
        $legend = $this->getLegendarySeedCount($request);

        //Total points per hour
        $total =
            ($dying * 2) + ($legendary * 1.5) + ($old * 1) + ($huge * 0.75) + ($large * 0.50) + ($regular * 0.25)
            + ($participant * 0.25) + ($teamplayer * 0.50) + ($committed * 0.75) + ($mvp * 1) + ($legend * 2);

        $daily = $total * 24;
        $weekly = $total * 24 * 7;
        $monthly = $total * 24 * 30;
        $yearly = $total * 24 * 365;
        $minute = $total / 60;
        $second = $minute / 60;

        return view('bonus.index', [
            'userbon'           => $userbon,
            'dying'             => $dying,
            'legendary'         => $legendary,
            'old'               => $old,
            'huge'              => $huge,
            'large'             => $large,
            'regular'           => $regular,
            'participant'       => $participant,
            'teamplayer'        => $teamplayer,
            'committed'         => $committed,
            'mvp'               => $mvp,
            'legend'            => $legend,
            'total'             => $total,
            'daily'             => $daily,
            'weekly'            => $weekly,
            'monthly'           => $monthly,
            'yearly'            => $yearly,
            'username'          => $username,
            'minute'            => $minute,
            'second'            => $second,
        ]);
    }

    /**
     * Exchange Points For A Item.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function exchange(Request $request, $id)
    {
        $user = $request->user();
        $userbon = $user->seedbonus;

        $BonExchange = new BonExchange();
        $itemCost = $BonExchange->getItemCost($id);

        if ($userbon >= $itemCost) {
            $flag = $this->doItemExchange($user->id, $id);

            if (!$flag) {
                return redirect()->route('bonus_store')
                    ->withErrors('Bonus Exchange Failed!');
            }

            $user->seedbonus -= $itemCost;
            $user->save();
        } else {
            return redirect()->route('bonus_store')
                ->withErrors('Bonus Exchange Failed!');
        }

        return redirect()->route('bonus_store')
            ->withSuccess('Bonus Exchange Successful');
    }

    /**
     * Do Item Exchange.
     *
     * @param $userID
     * @param $itemID
     *
     * @return string
     */
    public function doItemExchange($userID, $itemID)
    {
        $current = Carbon::now();
        $item = BonExchange::where('id', '=', $itemID)->get()->toArray()[0];

        $user_acc = User::findOrFail($userID);
        $activefl = PersonalFreeleech::where('user_id', '=', $user_acc->id)->first();
        $bon_transactions = new BonTransactions();

        if ($item['upload'] == true) {
            $user_acc->uploaded += $item['value'];
            $user_acc->save();
        } elseif ($item['download'] == true) {
            if ($user_acc->downloaded >= $item['value']) {
                $user_acc->downloaded -= $item['value'];
                $user_acc->save();
            } else {
                return false;
            }
        } elseif ($item['personal_freeleech'] == true) {
            if (!$activefl) {
                $personal_freeleech = new PersonalFreeleech();
                $personal_freeleech->user_id = $user_acc->id;
                $personal_freeleech->save();

                // Send Private Message
                $pm = new PrivateMessage();
                $pm->sender_id = 1;
                $pm->receiver_id = $user_acc->id;
                $pm->subject = 'Personal 24 Hour Freeleech Activated';
                $pm->message = sprintf('Your [b]Personal 24 Hour Freeleech[/b] session has started! It will expire on %s [b]', $current->addDays(1)->toDayDateTimeString()).config('app.timezone').'[/b]! 
                [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]';
                $pm->save();
            } else {
                return false;
            }
        } elseif ($item['invite'] == true) {
            if ($user_acc->invites += $item['value']) {
                $user_acc->save();
            } else {
                return false;
            }
        }

        $bon_transactions->itemID = $item['id'];
        $bon_transactions->name = $item['description'];
        $bon_transactions->cost = $item['value'];
        $bon_transactions->sender = $userID;
        $bon_transactions->comment = $item['description'];
        $bon_transactions->torrent_id = null;
        $bon_transactions->save();

        return true;
    }

    /**
     * Gift Points To A User.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function sendGift(Request $request)
    {
        $user = $request->user();

        $v = validator($request->all(), [
            'to_username'   => 'required|exists:users,username|max:180',
            'bonus_points'  => sprintf('required|numeric|min:1|max:%s', $user->seedbonus),
            'bonus_message' => 'required|string',
        ]);

        $dest = 'default';
        if ($request->has('dest') && $request->input('dest') == 'profile') {
            $dest = 'profile';
        }

        if ($v->passes()) {
            $recipient = User::where('username', '=', $request->input('to_username'))->first();

            if (!$recipient || $recipient->id == $user->id) {
                return redirect()->route('bonus_store')
                    ->withErrors('Unable to find specified user');
            }

            $value = $request->input('bonus_points');
            $recipient->seedbonus += $value;
            $recipient->save();

            $user->seedbonus -= $value;
            $user->save();

            $transaction = new BonTransactions();
            $transaction->itemID = 0;
            $transaction->name = 'gift';
            $transaction->cost = $value;
            $transaction->sender = $user->id;
            $transaction->receiver = $recipient->id;
            $transaction->comment = $request->input('bonus_message');
            $transaction->torrent_id = null;
            $transaction->save();

            if ($user->id != $recipient->id && $recipient->acceptsNotification($request->user(), $recipient, 'bon', 'show_bon_gift')) {
                $recipient->notify(new NewBon('gift', $user->username, $transaction));
            }

            $profile_url = hrefProfile($user);
            $recipient_url = hrefProfile($recipient);

            $this->chat->systemMessage(
                sprintf('[url=%s]%s[/url] has gifted %s BON to [url=%s]%s[/url]', $profile_url, $user->username, $value, $recipient_url, $recipient->username)
            );

            if ($dest == 'profile') {
                return redirect()->route('users.show', ['username' => $recipient->username])
                    ->withSuccess('Gift Sent');
            }

            return redirect()->route('bonus_gift')
                ->withSuccess('Gift Sent');
        }
        $v = validator($request->all(), [
            'to_username' => 'required|exists:users,username|max:180',
        ]);
        if ($v->passes()) {
            $recipient = User::where('username', 'LIKE', $request->input('to_username'))->first();

            if (!$recipient || $recipient->id == $user->id) {
                return redirect()->route('bonus_store')
                    ->withErrors('Unable to find specified user');
            }

            if ($dest == 'profile') {
                return redirect()->route('users.show', ['username' => $recipient->username])
                    ->withErrors('You Must Enter An Amount And Message!');
            }

            return redirect()->route('bonus_gift')
                ->withErrors('You Must Enter An Amount And Message!');
        }

        return redirect()->route('bonus_store')
            ->withErrors('Unable to find specified user');
    }

    /**
     * Tip Points To A Uploader.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function tipUploader(Request $request, $id)
    {
        $user = $request->user();
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $uploader = User::where('id', '=', $torrent->user_id)->first();

        $tip_amount = $request->input('tip');
        if ($tip_amount > $user->seedbonus) {
            return redirect()->route('torrent', ['id' => $torrent->id])
                ->withErrors('Your To Broke To Tip The Uploader!');
        }
        if ($user->id == $torrent->user_id) {
            return redirect()->route('torrent', ['id' => $torrent->id])
                ->withErrors('You Cannot Tip Yourself!');
        }
        if ($tip_amount <= 0) {
            return redirect()->route('torrent', ['id' => $torrent->id])
                ->withErrors('You Cannot Tip A Negative Amount!');
        }
        $uploader->seedbonus += $tip_amount;
        $uploader->save();

        $user->seedbonus -= $tip_amount;
        $user->save();

        $transaction = new BonTransactions();
        $transaction->itemID = 0;
        $transaction->name = 'tip';
        $transaction->cost = $tip_amount;
        $transaction->sender = $user->id;
        $transaction->receiver = $uploader->id;
        $transaction->comment = 'tip';
        $transaction->torrent_id = $torrent->id;
        $transaction->save();

        if ($uploader->acceptsNotification($request->user(), $uploader, 'torrent', 'show_torrent_tip')) {
            $uploader->notify(new NewUploadTip('torrent', $user->username, $tip_amount, $torrent));
        }

        return redirect()->route('torrent', ['id' => $torrent->id])
            ->withSuccess('Your Tip Was Successfully Applied!');
    }

    /**
     * Tip Points To A Poster.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function tipPoster(Request $request)
    {
        $user = $request->user();

        if ($request->has('post') && $request->input('post') > 0) {
            $p = (int) $request->input('post');
            $post = Post::with('topic')->findOrFail($p);
            $poster = User::where('id', '=', $post->user_id)->firstOrFail();
        } else {
            abort(404);
        }

        $tip_amount = $request->input('tip');
        if ($tip_amount > $user->seedbonus) {
            return redirect()->route('forum_topic', ['id' => $post->topic->id])
                ->withErrors('You Are To Broke To Tip The Poster!');
        }
        if ($user->id == $poster->id) {
            return redirect()->route('forum_topic', ['id' => $post->topic->id])
                ->withErrors('You Cannot Tip Yourself!');
        }
        if ($tip_amount <= 0) {
            return redirect()->route('forum_topic', ['id' => $post->topic->id])
                ->withErrors('You Cannot Tip A Negative Amount!');
        }
        $poster->seedbonus += $tip_amount;
        $poster->save();

        $user->seedbonus -= $tip_amount;
        $user->save();

        $transaction = new BonTransactions();
        $transaction->itemID = 0;
        $transaction->name = 'tip';
        $transaction->cost = $tip_amount;
        $transaction->sender = $user->id;
        $transaction->receiver = $poster->id;
        $transaction->comment = 'tip';
        $transaction->post_id = $post->id;
        $transaction->save();

        $poster->notify(new NewPostTip('forum', $user->username, $tip_amount, $post));

        return redirect()->route('forum_topic', ['id' => $post->topic->id])
            ->withSuccess('Your Tip Was Successfully Applied!');
    }

    /**
     * @method getDyingCount
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return int
     */
    public function getDyingCount(Request $request)
    {
        $user = $request->user();

        return DB::table('peers')
            ->select('peers.hash')->distinct()
            ->join('torrents', 'torrents.id', '=', 'peers.torrent_id')
            ->where('peers.seeder', 1)
            ->where('torrents.seeders', 1)
            ->where('torrents.times_completed', '>', 2)
            ->where('peers.user_id', $user->id)
            ->count();
    }

    /**
     * @method getLegendaryCount
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return int
     */
    public function getLegendaryCount(Request $request)
    {
        $user = $request->user();

        return DB::table('peers')
            ->select('peers.hash')->distinct()
            ->join('torrents', 'torrents.id', '=', 'peers.torrent_id')
            ->whereRaw('torrents.created_at < date_sub(now(), interval 12 month)')
            ->whereRaw('date_sub(peers.created_at,interval 30 minute) < now()')
            ->where('peers.seeder', 1)
            ->where('peers.user_id', $user->id)
            ->count();
    }

    /**
     * @method getOldCount
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return int
     */
    public function getOldCount(Request $request)
    {
        $user = $request->user();

        return DB::table('peers')
            ->select('peers.hash')->distinct()
            ->join('torrents', 'torrents.id', '=', 'peers.torrent_id')
            ->whereRaw('torrents.created_at < date_sub(now(), Interval 6 month)')
            ->whereRaw('torrents.created_at > date_sub(now(), interval 12 month)')
            ->whereRaw('date_sub(peers.created_at,interval 30 minute) < now()')
            ->where('peers.seeder', 1)
            ->where('peers.user_id', $user->id)
            ->count();
    }

    /**
     * @method getHugeCount
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return int
     */
    public function getHugeCount(Request $request)
    {
        $user = $request->user();

        return DB::table('peers')
            ->select('peers.hash')->distinct()
            ->join('torrents', 'torrents.id', '=', 'peers.torrent_id')
            ->where('peers.seeder', 1)
            ->where('torrents.size', '>=', 1073741824 * 100)
            ->where('peers.user_id', $user->id)
            ->count();
    }

    /**
     * @method getLargeCount
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return int
     */
    public function getLargeCount(Request $request)
    {
        $user = $request->user();

        return DB::table('peers')
            ->select('peers.hash')->distinct()
            ->join('torrents', 'torrents.id', '=', 'peers.torrent_id')
            ->where('peers.seeder', 1)
            ->where('torrents.size', '>=', 1073741824 * 25)
            ->where('torrents.size', '<', 1073741824 * 100)
            ->where('peers.user_id', $user->id)
            ->count();
    }

    /**
     * @method getRegularCount
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return int
     */
    public function getRegularCount(Request $request)
    {
        $user = $request->user();

        return DB::table('peers')
            ->select('peers.hash')->distinct()
            ->join('torrents', 'torrents.id', '=', 'peers.torrent_id')
            ->where('peers.seeder', 1)
            ->where('torrents.size', '>=', 1073741824)
            ->where('torrents.size', '<', 1073741824 * 25)
            ->where('peers.user_id', $user->id)
            ->count();
    }

    /**
     * @method getParticipaintSeedCount
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return int
     */
    public function getParticipaintSeedCount(Request $request)
    {
        $user = $request->user();

        return DB::table('history')
            ->select('history.seedtime')->distinct()
            ->join('torrents', 'torrents.info_hash', '=', 'history.info_hash')
            ->where('history.active', 1)
            ->where('history.seedtime', '>=', 2592000)
            ->where('history.seedtime', '<', 2592000 * 2)
            ->where('history.user_id', $user->id)
            ->count();
    }

    /**
     * @method getParticipaintSeedCount
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return int
     */
    public function getTeamPlayerSeedCount(Request $request)
    {
        $user = $request->user();

        return DB::table('history')
            ->select('history.seedtime')->distinct()
            ->join('torrents', 'torrents.info_hash', '=', 'history.info_hash')
            ->where('history.active', 1)
            ->where('history.seedtime', '>=', 2592000 * 2)
            ->where('history.seedtime', '<', 2592000 * 3)
            ->where('history.user_id', $user->id)
            ->count();
    }

    /**
     * @method getParticipaintSeedCount
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return int
     */
    public function getCommitedSeedCount(Request $request)
    {
        $user = $request->user();

        return DB::table('history')
            ->select('history.seedtime')->distinct()
            ->join('torrents', 'torrents.info_hash', '=', 'history.info_hash')
            ->where('history.active', 1)
            ->where('history.seedtime', '>=', 2592000 * 3)
            ->where('history.seedtime', '<', 2592000 * 6)
            ->where('history.user_id', $user->id)
            ->count();
    }

    /**
     * @method getParticipaintSeedCount
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return int
     */
    public function getMVPSeedCount(Request $request)
    {
        $user = $request->user();

        return DB::table('history')
            ->select('history.seedtime')->distinct()
            ->join('torrents', 'torrents.info_hash', '=', 'history.info_hash')
            ->where('history.active', 1)
            ->where('history.seedtime', '>=', 2592000 * 6)
            ->where('history.seedtime', '<', 2592000 * 12)
            ->where('history.user_id', $user->id)
            ->count();
    }

    /**
     * @method getParticipaintSeedCount
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return int
     */
    public function getLegendarySeedCount(Request $request)
    {
        $user = $request->user();

        return DB::table('history')
            ->select('history.seedtime')->distinct()
            ->join('torrents', 'torrents.info_hash', '=', 'history.info_hash')
            ->where('history.active', 1)
            ->where('history.seedtime', '>=', 2592000 * 12)
            ->where('history.user_id', $user->id)
            ->count();
    }
}

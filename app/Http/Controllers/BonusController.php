<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Mr.G
 */

namespace App\Http\Controllers;

use App\Repositories\ChatRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\BonExchange;
use App\BonTransactions;
use App\PrivateMessage;
use App\PersonalFreeleech;
use App\Torrent;
use Carbon\Carbon;
use \Toastr;

class BonusController extends Controller
{
    /**
     * @var ChatRepository
     */
    private $chat;

    public function __construct(ChatRepository $chat)
    {
        $this->chat = $chat;
    }

    /**
     * Show Bonus System
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bonus()
    {
        $user = auth()->user();
        $users = User::oldest('username')->get();
        $userbon = $user->getSeedbonus();
        $activefl = PersonalFreeleech::where('user_id', $user->id)->first();

        $BonExchange = new BonExchange();

        $uploadOptions = $BonExchange->getUploadOptions();
        $downloadOptions = $BonExchange->getDownloadOptions();
        $personalFreeleech = $BonExchange->getPersonalFreeleechOption();
        $invite = $BonExchange->getInviteOption();

        //Dying Torrent
        $dying = $this->getDyingCount();
        //Legendary Torrents
        $legendary = $this->getLegendaryCount();
        //Old Torrents
        $old = $this->getOldCount();
        //Large Torrents
        $huge = $this->getHugeCount();
        //Large Torrents
        $large = $this->getLargeCount();
        //Everyday Torrents
        $regular = $this->getRegularCount();

        //Participant Seeder
        $participant = $this->getParticipaintSeedCount();
        //TeamPlayer Seeder
        $teamplayer = $this->getTeamPlayerSeedCount();
        //Committed Seeder
        $committed = $this->getCommitedSeedCount();
        //MVP Seeder
        $mvp = $this->getMVPSeedCount();
        //Legend Seeder
        $legend = $this->getLegendarySeedCount();

        //Total points per hour
        $total =
            ($dying * 2) + ($legendary * 1.5) + ($old * 1) + ($huge * 0.75) + ($large * 0.50) + ($regular * 0.25)
            + ($participant * 0.25) + ($teamplayer * 0.50) + ($committed * 0.75) + ($mvp * 1) + ($legend * 2);

        return view('bonus.bonus', [
            'users' => $users,
            'userbon' => $userbon,
            'activefl' => $activefl,
            'uploadOptions' => $uploadOptions,
            'downloadOptions' => $downloadOptions,
            'personalFreeleech' => $personalFreeleech,
            'invite' => $invite,
            'dying' => $dying,
            'legendary' => $legendary,
            'old' => $old,
            'huge' => $huge,
            'large' => $large,
            'regular' => $regular,
            'participant' => $participant,
            'teamplayer' => $teamplayer,
            'committed' => $committed,
            'mvp' => $mvp,
            'legend' => $legend,
            'total' => $total
        ]);
    }

    /**
     * Exchange Points For A Item
     *
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function exchange($id)
    {
        $user = auth()->user();
        $userbon = $user->seedbonus;

        $BonExchange = new BonExchange();
        $itemCost = $BonExchange->getItemCost($id);

        if ($userbon >= $itemCost) {
            $flag = $this->doItemExchange($user->id, $id);

            if (!$flag) {
                return redirect('/bonus')
                    ->with(Toastr::error('Bonus Exchange Failed!', 'Whoops!', ['options']));
            }

            $user->seedbonus -= $itemCost;
            $user->save();
        } else {
            return redirect('/bonus')
                ->with(Toastr::error('Bonus Exchange Failed!', 'Whoops!', ['options']));
        }

        return redirect('/bonus')
            ->with(Toastr::success('Bonus Exchange Successful', 'Yay!', ['options']));
    }

    /**
     * Do Item Exchange
     *
     * @param $userID
     * @param $itemID
     * @return string
     */
    public function doItemExchange($userID, $itemID)
    {
        $item = BonExchange::where('id', $itemID)->get()->toArray()[0];

        $user_acc = User::findOrFail($userID);
        $activefl = PersonalFreeleech::where('user_id', $user_acc->id)->first();
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
     * Gift Points To A User
     *
     * @param \Illuminate\Http\Request $request
     * @return Illuminate\Http\RedirectResponse
     */
    public function gift(Request $request)
    {
        $user = auth()->user();

            $v = validator($request->all(), [
                'to_username' => "required|exists:users,username|max:180",
                'bonus_points' => "required|numeric|min:1|max:{$user->seedbonus}",
                'bonus_message' => "required|string"
            ]);

            if ($v->passes()) {
                $recipient = User::where('username', 'LIKE', $request->input('to_username'))->first();

                if (!$recipient || $recipient->id == $user->id) {
                    return redirect('/bonus')
                        ->with(Toastr::error('Unable to find specified user', 'Whoops!', ['options']));
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

                $profile_url = hrefProfile($user);
                $recipient_url = hrefProfile($recipient);

                $this->chat->systemMessage(
                    "[url={$profile_url}]{$user->username}[/url] has gifted {$value} BON to [url={$recipient_url}]{$recipient->username}[/url]"
                );

                $pm = new PrivateMessage;
                $pm->sender_id = $user->id;
                $pm->receiver_id = $recipient->id;
                $pm->subject = "You Have Received A Gift";
                $pm->message = $transaction->comment;
                $pm->save();

                return redirect('/bonus')
                    ->with(Toastr::success('Gift Sent', 'Yay!', ['options']));
            } else {
                return redirect('/bonus')
                    ->with(Toastr::error('Gifting Failed', 'Whoops!', ['options']));
            }
    }

    /**
     * Tip Points To A Uploader
     *
     * @param \Illuminate\Http\Request $request
     * @param $slug
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function tipUploader(Request $request, $slug, $id)
    {
        $user = auth()->user();
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $uploader = User::where('id', $torrent->user_id)->first();

        $tip_amount = $request->input('tip');
        if ($tip_amount > $user->seedbonus) {
            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                ->with(Toastr::error('Your To Broke To Tip The Uploader!', 'Whoops!', ['options']));
        }
        if ($user->id == $torrent->user_id) {
            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                ->with(Toastr::error('You Cannot Tip Yourself!', 'Whoops!', ['options']));
        }
        if ($tip_amount <= 0) {
            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
                ->with(Toastr::error('You Cannot Tip A Negative Amount!', 'Whoops!', ['options']));
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

        $pm = new PrivateMessage;
        $pm->sender_id = 1;
        $pm->receiver_id = $uploader->id;
        $pm->subject = "You Have Received A BON Tip";
        $pm->message = "Member " . $user->username . " has left a tip of " . $tip_amount . " BON on your upload " . $torrent->name . ".";
        $pm->save();

        return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])
            ->with(Toastr::success('Your Tip Was Successfully Applied!', 'Yay!', ['options']));
    }


    /**
     * @method getDyingCount
     *
     * @access public
     *
     * @return Integer
     */
    public function getDyingCount()
    {
        $user = auth()->user();

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
     * @access public
     *
     * @return Integer
     */
    public function getLegendaryCount()
    {
        $user = auth()->user();

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
     * @access public
     *
     * @return Integer
     */
    public function getOldCount()
    {
        $user = auth()->user();

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
     * @access public
     *
     * @return Integer
     */
    public function getHugeCount()
    {
        $user = auth()->user();

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
     * @access public
     *
     * @return Integer
     */
    public function getLargeCount()
    {
        $user = auth()->user();

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
     * @access public
     *
     * @return Integer
     */
    public function getRegularCount()
    {
        $user = auth()->user();

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
     * @access public
     *
     * @return Integer
     */
    public function getParticipaintSeedCount()
    {
        $user = auth()->user();

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
     * @access public
     *
     * @return Integer
     */
    public function getTeamPlayerSeedCount()
    {
        $user = auth()->user();

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
     * @access public
     *
     * @return Integer
     */
    public function getCommitedSeedCount()
    {
        $user = auth()->user();

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
     * @access public
     *
     * @return Integer
     */
    public function getMVPSeedCount()
    {
        $user = auth()->user();

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
     * @access public
     *
     * @return Integer
     */
    public function getLegendarySeedCount()
    {
        $user = auth()->user();

        return DB::table('history')
            ->select('history.seedtime')->distinct()
            ->join('torrents', 'torrents.info_hash', '=', 'history.info_hash')
            ->where('history.active', 1)
            ->where('history.seedtime', '>=', 2592000 * 12)
            ->where('history.user_id', $user->id)
            ->count();
    }
}

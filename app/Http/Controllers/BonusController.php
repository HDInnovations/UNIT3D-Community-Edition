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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\BonExchange;
use App\BonTransactions;
use App\Shoutbox;
use App\PrivateMessage;
use App\PersonalFreeleech;
use App\Torrent;
use Carbon\Carbon;
use \Toastr;

class BonusController extends Controller
{

    /**
     * Bonus System
     *
     *
     * @access public
     * @return view::make bonus.bonus
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

        //Participaint Seeder
        $participaint = $this->getParticipaintSeedCount();
        //Participaint Seeder
        $teamplayer = $this->getTeamPlayerSeedCount();
        //Participaint Seeder
        $commited = $this->getCommitedSeedCount();
        //Participaint Seeder
        $mvp = $this->getMVPSeedCount();
        //Participaint Seeder
        $legendary = $this->getLegendarySeedCount();

        //Total points per hour
        $total =
            ($dying * 2) + ($legendary * 1.5) + ($old * 1) + ($huge * 0.75) + ($large * 0.50) + ($regular * 0.25)
            + ($participaint * 0.25) + ($teamplayer * 0.50) + ($commited * 0.75) + ($mvp * 1) + ($legendary * 2);

        return view('bonus.bonus', ['activefl' => $activefl, 'userbon' => $userbon, 'uploadOptions' => $uploadOptions,
            'downloadOptions' => $downloadOptions, 'personalFreeleech' => $personalFreeleech, 'invite' => $invite,
            'dying' => $dying, 'legendary' => $legendary, 'old' => $old,
            'huge' => $huge, 'large' => $large, 'regular' => $regular,
            'participaint' => $participaint, 'teamplayer' => $teamplayer, 'commited' => $commited, 'mvp' => $mvp, 'legendary' => $legendary,
            'total' => $total, 'users' => $users]);
    }

    /**
     * @method exchange
     *
     * @access public
     *
     * This method is used when a USER exchanges their points into items
     *
     * @param $id This refers to the ID of the exchange item
     *
     * @return Redirect::to /bonus
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
                return redirect('/bonus')->with(Toastr::error('Bonus Exchange Failed!', 'Whoops!', ['options']));
            }

            $user->seedbonus -= $itemCost;
            $user->save();
        } else {
            return redirect('/bonus')->with(Toastr::error('Bonus Exchange Failed!', 'Whoops!', ['options']));
        }

        return redirect('/bonus')->with(Toastr::success('Bonus Exchange Successful', 'Yay!', ['options']));
    }

    /**
     * @method doItemExchange
     *
     * @access public
     *
     * @param $userID The person initiating the transaction
     * @param $itemID This is the exchange item ID
     *
     * @return boolean
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
     * @method gift
     *
     * @access public
     *
     * @return void
     */
    public function gift(Request $request)
    {
        $user = auth()->user();

        if ($request->isMethod('POST')) {
            $v = validator($request->all(), [
                'to_username' => "required|exists:users,username|max:180",
                'bonus_points' => "required|numeric|min:1|max:{$user->seedbonus}",
                'bonus_message' => "required|string"
            ]);

            if ($v->passes()) {
                $recipient = User::where('username', 'LIKE', $request->input('to_username'))->first();

                if (!$recipient || $recipient->id == $user->id) {
                    return redirect('/bonus')->with(Toastr::error('Unable to find specified user', 'Whoops!', ['options']));
                }

                $value = $request->input('bonus_points');
                $recipient->seedbonus += $value;
                $recipient->save();

                $user->seedbonus -= $value;
                $user->save();

                $transaction = new BonTransactions([
                    'itemID' => 0,
                    'name' => 'gift',
                    'cost' => $value,
                    'sender' => $user->id,
                    'receiver' => $recipient->id,
                    'comment' => $request->input('bonus_message'),
                    'torrent_id' => null
                ]);
                $transaction->save();

                $appurl = config('app.url');
                Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "User [url={$appurl}/" . $user->username . "." . $user->id . "]" . $user->username . "[/url] has gifted " . $value . "BON to [url={$appurl}/"
                    . $recipient->username . "." . $recipient->id . "]" . $recipient->username . "[/url]"]);
                cache()->forget('shoutbox_messages');

                PrivateMessage::create(['sender_id' => $user->id, 'reciever_id' => $recipient->id, 'subject' => "You Have Recieved A Gift", 'message' => $transaction->comment]);

                return redirect('/bonus')->with(Toastr::success('Gift Sent', 'Yay!', ['options']));
            } else {
                return redirect('/bonus')->with(Toastr::error('Gifting Failed', 'Whoops!', ['options']));
            }
        } else {
            return redirect('/bonus')->with(Toastr::error('Unknown error occurred', 'Whoops!', ['options']));
        }
    }

    /**
     * @method tipUploader
     *
     * @access public
     *
     * @return void
     */
    public function tipUploader(Request $request, $slug, $id)
    {
        $user = auth()->user();
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $uploader = User::where('id', $torrent->user_id)->first();

        $tip_amount = $request->input('tip');
        if ($tip_amount > $user->seedbonus) {
            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::error('Your To Broke To Tip The Uploader!', 'Whoops!', ['options']));
        }
        if ($user->id == $torrent->user_id) {
            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::error('You Cannot Tip Yourself!', 'Whoops!', ['options']));
        }
        if ($tip_amount < 0) {
            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::error('You Cannot Tip A Negative Amount!', 'Whoops!', ['options']));
        }
        $uploader->seedbonus += $tip_amount;
        $uploader->save();

        $user->seedbonus -= $tip_amount;
        $user->save();

        $transaction = new BonTransactions([
            'itemID' => 0,
            'name' => 'tip',
            'cost' => $tip_amount,
            'sender' => $user->id,
            'receiver' => $uploader->id,
            'comment' => 'tip',
            'torrent_id' => $torrent->id
        ]);
        $transaction->save();

        // Insert the Recipient notification below
        PrivateMessage::create(['sender_id' => "1", 'reciever_id' => $uploader->id, 'subject' => "You Have Recieved A BON Tip", 'message' => "Member " . $user->username . " has left a tip of " . $tip_amount . " BON on your upload " . $torrent->name . "."]);
        // End insert recipient notification here

        return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::success('Your Tip Was Successfully Applied!', 'Yay!', ['options']));
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

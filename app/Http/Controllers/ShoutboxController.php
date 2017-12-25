<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\Shoutbox;
use App\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

use Cache;
use Carbon\Carbon;
use Decoda\Decoda;
use \Toastr;

class ShoutboxController extends Controller
{
    /**
     * Send Shout
     *
     *
     */
    public function send()
    {
        $checkSendRate = Shoutbox::where('user', '=', Auth::user()->id)->where('created_at', '>=', Carbon::now()->subSeconds(2))->first();
        if ($checkSendRate) {
            return 'Wait 2 Seconds Between Posts Please';
        }

        if (Auth::user()->can_chat == 0) {
            return 'Your Chat Banned';
        }

        $v = Validator::make(Request::all(), [
            'message' => 'required|min:1|regex:/^[(a-zA-Z\-)]+$/u'
        ]);
        if ($v->fails()) {
            Toastr::error('There was a error with your input!', 'Error!', ['options']);
        }
        if (Request::ajax()) {
            preg_match_all('/(@\w+)/', Request::get('message'), $mentions);
            $mentionIDs = [];
            foreach ($mentions[0] as $mention) {
                $findUser = User::where('username', 'LIKE', '%' . str_replace('@', '', $mention) . '%')->first();
                if (!empty($findUser->id)) {
                    $mentionIDs[] = $findUser['id'];
                }
            }
            $mentions = implode(',', $mentionIDs);
            if (count($mentions) > 0) {
                $insertMessage = Shoutbox::create(['user' => Auth::user()->id, 'message' => Request::get('message'), 'mentions' => $mentions]);
            } else {
                $insertMessage = Shoutbox::create(['user' => Auth::user()->id, 'message' => Request::get('message')]);
            }

            if (Auth::user()->image != null) {
                $flag = true;
                $avatar = '<img class="profile-avatar tiny pull-left" src="/files/img/' . Auth::user()->image . '">';
            } else {
                $flag = true;
                $avatar = '<img class="profile-avatar tiny pull-left" src="/img/profil.png">';
            }

            if (Auth::user()->isOnline()) {
                $flag = true;
                $online = '<i class="fa fa-circle text-green" data-toggle="tooltip" title="" data-original-title="User Is Online!"></i>';
            } else {
                $flag = true;
                $online = '<i class="fa fa-circle text-red" data-toggle="tooltip" title="" data-original-title="User Is Offline!"></i>';
            }

            $appurl = env('APP_URL', 'http://unit3d.site');
            $data = '<li class="list-group-item">
      ' . ($flag ? $avatar : "") . '
      <h4 class="list-group-item-heading"><span class="badge-user text-bold"><i class="' . (Auth::user()->group->icon) . '" data-toggle="tooltip" title="" data-original-title="' . (Auth::user()->group->name) . '"></i>&nbsp;<a style="color:' . (Auth::user()->group->color) . ';" href=\'' . $appurl . '/' . Auth::user()->username . '.' . Auth::user()->id . '\'>'
                . Auth::user()->username . '</a>
      ' . ($flag ? $online : "") . '
      </span>&nbsp;<span class="text-muted"><small><em>' . Carbon::now()->diffForHumans() . '</em></small></span>
      </h4>
      <p class="message-content">' . e(Request::get('message')) . '</p>
      </li>';

            Cache::forget('shoutbox_messages');
            return Response::json(['success' => true, 'data' => $data]);
        }
    }

    /**
     * Fetch Shout
     *
     *
     */
    public function fetch()
    {
        if (Request::ajax()) {
            $getData = Cache::remember('shoutbox_messages', 1440, function () {
                return Shoutbox::orderBy('created_at', 'desc')->take(50)->get();
            });
            $getData = $getData->reverse();
            $data = [];
            $flag = false;
            foreach ($getData as $messages) {
                $class = '';
                if (in_array(Auth::user()->id, explode(',', $messages->mentions))) {
                    $class = 'mentioned';
                }

                if ($messages->poster->image != null) {
                    $flag = true;
                    $avatar = '<img class="profile-avatar tiny pull-left" src="/files/img/' . $messages->poster->image . '">';
                } else {
                    $flag = true;
                    $avatar = '<img class="profile-avatar tiny pull-left" src="img/profil.png">';
                }

                if (Auth::user()->group->is_modo || $messages->poster->id == Auth::user()->id) {
                    $flag = true;
                    $appurl = env('APP_URL', 'http://unit3d.site');
                    $delete = '<a title="Delete Shout" href=\'' . $appurl . '/shoutbox/delete/' . $messages->id . '\'><i class="pull-right fa fa-lg fa-times"></i></a>';
                } else {
                    $flag = true;
                    $delete = '';
                }

                if ($messages->poster->isOnline()) {
                    $flag = true;
                    $online = '<i class="fa fa-circle text-green" data-toggle="tooltip" title="" data-original-title="User Is Online!"></i>';
                } else {
                    $flag = true;
                    $online = '<i class="fa fa-circle text-red" data-toggle="tooltip" title="" data-original-title="User Is Offline!"></i>';
                }

                $appurl = env('APP_URL', 'http://unit3d.site');
                $data[] = '<li class="list-group-item ' . $class . '">
                       ' . ($flag ? $avatar : "") . '
                       <h4 class="list-group-item-heading"><span class="badge-user text-bold"><i class="' . ($messages->poster->group->icon) . '" data-toggle="tooltip" title="" data-original-title="' . ($messages->poster->group->name) . '"></i>&nbsp;<a style="color:' . ($messages->poster->group->color) . ';" href=\'' . $appurl . '/' . e($messages->poster->username) . '.' . e($messages->poster->id) . '\'>'
                    . e($messages->poster->username) . '</a>
                       ' . ($flag ? $online : "") . '
                       </span>&nbsp;<span class="text-muted"><small><em>' . ($messages->created_at->diffForHumans()) . '</em></small></span>
                       </h4>
                       <p class="message-content">
                       ' . \LaravelEmojiOne::toImage(Shoutbox::getMessageHtml($messages->message)) . '
                       ' . ($flag ? $delete : "") . '
                       </p></li>';
            }
            return Response::json(['success' => true, 'data' => $data]);
        }
    }

    /**
     * Delete Shout
     *
     * @param $id
     */
    public function deleteShout($id)
    {
        $shout = Shoutbox::find($id);
        if (Auth::user()->group->is_modo || Auth::user()->id == $shout->poster->id) {
            Shoutbox::where('id', '=', $id)->delete();
            Cache::forget('shoutbox_messages');
            return redirect()->back()->with(Toastr::success('Shout Has Been Deleted.', 'Yay!', ['options']));
        } else {
            return redirect()->back()->with(Toastr::error('This is not your shout to delete.', 'Bro!', ['options']));
        }
    }
}

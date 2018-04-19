<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Poll;
use App\Option;
use App\Shoutbox;
use App\Http\Requests\StorePoll;
use Cache;
use \Toastr;

class PollController extends Controller
{
    public function polls()
    {
        $polls = Poll::latest()->paginate(25);
        return view('Staff.poll.polls', compact('polls'));
    }

    public function poll($id)
    {
        $poll = Poll::where('id', $id)->firstOrFail();
        return view('Staff.poll.poll', compact('poll'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Staff.poll.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePoll $request)
    {
        $user = auth()->user();

        if (auth()->check()) {
            $poll = $user->polls()->create($request->all());
        } else {
            $poll = Poll::create($request->all());
        }

        $options = collect($request->input('options'))->map(function ($value) {
            return new Option(['name' => $value]);
        });
        $poll->options()->saveMany($options);

        // Activity Log
        \LogActivity::addToLog("Staff Member {$user->username} has created a new poll {$poll->title}.");

        // Auto Shout
        $appurl = config('app.url');
        Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "A new poll has been created [url={$appurl}/poll/{$poll->slug}]{$poll->title}[/url] vote on it now! :slight_smile:"]);
        Cache::forget('shoutbox_messages');

        return redirect('poll/' . $poll->slug)->with(Toastr::success('Your poll has been created.', 'Yay!', ['options']));
    }
}

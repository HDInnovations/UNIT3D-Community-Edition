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

use App\Poll;
use App\Option;
use Brian2694\Toastr\Toastr;
use App\Http\Requests\StorePoll;
use App\Http\Controllers\Controller;
use App\Repositories\ChatRepository;

class PollController extends Controller
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
     * PollController Constructor.
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
     * Show All Polls.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function polls()
    {
        $polls = Poll::latest()->paginate(25);

        return view('Staff.poll.polls', ['polls' => $polls]);
    }

    /**
     * Show A Poll.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function poll($id)
    {
        $poll = Poll::where('id', '=', $id)->firstOrFail();

        return view('Staff.poll.poll', ['poll' => $poll]);
    }

    /**
     * Poll Add Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('Staff.poll.create');
    }

    /**
     * Add A Poll.
     *
     * @param StorePoll $request
     *
     * @return Illuminate\Http\RedirectResponse
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

        $poll_url = hrefPoll($poll);

        $this->chat->systemMessage(
            "A new poll has been created [url={$poll_url}]{$poll->title}[/url] vote on it now! :slight_smile:"
        );

        return redirect('poll/'.$poll->slug)
            ->with($this->toastr->success('Your poll has been created.', 'Yay!', ['options']));
    }
}

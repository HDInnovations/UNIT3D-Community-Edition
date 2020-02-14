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

use App\Http\Requests\VoteOnPoll;
use App\Models\Option;
use App\Models\Poll;
use App\Models\Voter;
use App\Repositories\ChatRepository;
use Illuminate\Http\Request;

class PollController extends Controller
{
    /**
     * @var ChatRepository
     */
    private $chat;

    /**
     * PollController Constructor.
     *
     * @param ChatRepository $chat
     */
    public function __construct(ChatRepository $chat)
    {
        $this->chat = $chat;
    }

    /**
     * Show All Polls.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $polls = Poll::latest()->paginate(15);

        return view('poll.latest', ['polls' => $polls]);
    }

    /**
     * Show A Poll.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $slug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $slug)
    {
        $poll = Poll::whereSlug($slug)->firstOrFail();
        $user = $request->user();
        $user_has_voted = $poll->voters->where('user_id', '=', $user->id)->isNotEmpty();

        if ($user_has_voted) {
            return redirect('polls/'.$poll->slug.'/result')
                ->withInfo('You have already vote on this poll. Here are the results.');
        }

        return view('poll.show', compact('poll'));
    }

    /**
     * Vote On A Poll.
     *
     * @param VoteOnPoll $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function vote(VoteOnPoll $request)
    {
        $user = $request->user();
        $poll = Option::findOrFail($request->input('option.0'))->poll;

        foreach ($request->input('option') as $option) {
            Option::findOrFail($option)->increment('votes');
        }

        if (Voter::where('user_id', '=', $user->id)->where('poll_id', '=', $poll->id)->exists()) {
            return redirect('polls/'.$poll->slug.'/result')
                ->withErrors('Bro have already vote on this poll. Your vote has not been counted.');
        }

        if ($poll->ip_checking == 1) {
            $vote = new Voter();
            $vote->poll_id = $poll->id;
            $vote->user_id = $user->id;
            $vote->ip_address = $request->ip();
            $vote->save();
        }

        $poll_url = hrefPoll($poll);
        $profile_url = hrefProfile($user);

        $this->chat->systemMessage(
            "[url={$profile_url}]{$user->username}[/url] has voted on poll [url={$poll_url}]{$poll->title}[/url]"
        );

        return redirect('polls/'.$poll->slug.'/result')
            ->withSuccess('Your vote has been counted.');
    }

    /**
     * Show A Polls Results.
     *
     * @param $slug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function result($slug)
    {
        $poll = Poll::whereSlug($slug)->firstOrFail();
        $map = [
            'poll'        => $poll,
            'total_votes' => $poll->totalVotes(),
        ];

        return view('poll.result', $map);
    }
}

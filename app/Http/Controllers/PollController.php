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

/**
 * @see \Tests\Todo\Feature\Http\Controllers\Staff\PollControllerTest
 */
class PollController extends Controller
{
    /**
     * PollController Constructor.
     */
    public function __construct(private ChatRepository $chatRepository)
    {
    }

    /**
     * Show All Polls.
     */
    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        \abort_unless($request->user()->hasPrivilegeTo('polls_can_view'), 403);
        $polls = Poll::latest()->paginate(15);

        return \view('poll.latest', ['polls' => $polls]);
    }

    /**
     * Show A Poll.
     */
    public function show(Request $request, int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        \abort_unless($request->user()->hasPrivilegeTo('polls_can_view'), 403);
        $poll = Poll::findOrFail($id);
        $user = $request->user();
        $userHasVoted = $poll->voters->where('user_id', '=', $user->id)->isNotEmpty();

        if ($userHasVoted) {
            return \to_route('poll_results', ['id' => $poll->id])
                ->withInfo(\trans('poll.already-voted-result'));
        }

        return \view('poll.show', ['poll' => $poll]);
    }

    /**
     * Vote On A Poll.
     */
    public function vote(Request $request, VoteOnPoll $voteOnPoll)
    {
        \abort_unless($request->user()->hasPrivilegeTo('polls_can_vote'), 403);
        $user = $voteOnPoll->user();
        $poll = Option::findOrFail($voteOnPoll->input('option.0'))->poll;
        $voted = Voter::where('user_id', '=', $user->id)
            ->where('poll_id', '=', $poll->id)
            ->exists();
        if ($voted) {
            return \to_route('poll_results', ['id' => $poll->id])
                ->withErrors(\trans('poll.already-voted-error'));
        }

        // Operate options after validation
        foreach ($voteOnPoll->input('option') as $option) {
            Option::findOrFail($option)->increment('votes');
        }

        // Make voter after option operation completed
        $voter = new Voter();
        $voter->poll_id = $poll->id;
        $voter->user_id = $user->id;
        $voter->save();

        $pollUrl = \href_poll($poll);
        $profileUrl = \href_profile($user);

        $this->chatRepository->systemMessage(
            \sprintf('[url=%s]%s[/url] has voted on poll [url=%s]%s[/url]', $profileUrl, $user->username, $pollUrl, $poll->title)
        );

        return \to_route('poll_results', ['id' => $poll->id])
            ->withSuccess(\trans('poll.vote-counted'));
    }

    /**
     * Show A Polls Results.
     */
    public function result(Request $request, $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        \abort_unless($request->user()->hasPrivilegeTo('polls_can_view'), 403);
        $poll = Poll::findOrFail($id);
        $map = [
            'poll'        => $poll,
            'total_votes' => $poll->totalVotes(),
        ];

        return \view('poll.result', $map);
    }
}

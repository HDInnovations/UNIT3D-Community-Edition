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

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePoll;
use App\Http\Requests\UpdatePollRequest;
use App\Models\Option;
use App\Models\Poll;
use App\Repositories\ChatRepository;
use Exception;
use Illuminate\Support\Arr;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\Staff\PollControllerTest
 */
class PollController extends Controller
{
    /**
     * PollController Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Display All Polls.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.poll.index', [
            'polls' => Poll::latest()->paginate(25),
        ]);
    }

    /**
     * Show A Poll.
     */
    public function show(Poll $poll): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.poll.show', [
            'poll' => $poll,
        ]);
    }

    /**
     * Poll Add Form.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.poll.create');
    }

    /**
     * Store A New Poll.
     */
    public function store(StorePoll $request): \Illuminate\Http\RedirectResponse
    {
        $poll = Poll::create(['user_id' => $request->user()->id] + $request->safe()->only(['title', 'multiple_choice']));
        Option::upsert(array_map(fn ($item) => ['poll_id' => $poll->id] + $item, $request->safe()->only(['options'])['options']), ['id'], []);

        $this->chatRepository->systemMessage(
            sprintf('A new poll has been created [url=%s]%s[/url] vote on it now!', href_poll($poll), $poll->title)
        );

        return to_route('staff.polls.index')
            ->withSuccess('Your poll has been created.');
    }

    /**
     * Poll Edit Form.
     */
    public function edit(Poll $poll): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.poll.edit', [
            'poll' => $poll,
        ]);
    }

    /**
     * Update A New Poll.
     *
     * @throws Exception
     */
    public function update(UpdatePollRequest $request, Poll $poll): \Illuminate\Http\RedirectResponse
    {
        $poll->update($request->safe()->only(['title', 'multiple_choice']));

        $poll->options()
            ->whereNotIn('id', Arr::flatten($request->safe()->only(['options.*.id'])))
            ->delete();
        Option::upsert(array_map(fn ($item) => ['poll_id' => $poll->id] + $item, $request->safe()->only(['options'])['options']), ['id'], ['name']);

        $this->chatRepository->systemMessage(
            sprintf('A poll has been updated [url=%s]%s[/url] vote on it now!', href_poll($poll), $poll->title)
        );

        return to_route('staff.polls.index')
            ->withSuccess('Your poll has been edited.');
    }

    /**
     * Delete A Poll.
     *
     * @throws Exception
     */
    public function destroy(Poll $poll): \Illuminate\Http\RedirectResponse
    {
        $poll->options()->delete();
        $poll->delete();

        return to_route('staff.polls.index')
            ->withSuccess('Poll has successfully been deleted');
    }
}

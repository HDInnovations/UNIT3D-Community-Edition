<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePoll;
use App\Models\Option;
use App\Models\Poll;
use App\Repositories\ChatRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;

final class PollController extends Controller
{
    /**
     * @var ChatRepository
     */
    private ChatRepository $chat;
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private $viewFactory;
    /**
     * @var \Illuminate\Routing\Redirector
     */
    private $redirector;

    /**
     * PollController Constructor.
     *
     * @param  ChatRepository  $chat
     * @param  \Illuminate\Contracts\View\Factory  $viewFactory
     * @param  \Illuminate\Routing\Redirector  $redirector
     */
    public function __construct(ChatRepository $chat, Factory $viewFactory, Redirector $redirector)
    {
        $this->chat = $chat;
        $this->viewFactory = $viewFactory;
        $this->redirector = $redirector;
    }

    /**
     * Display All Polls.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(): Factory
    {
        $polls = Poll::latest()->paginate(25);

        return $this->viewFactory->make('Staff.poll.index', ['polls' => $polls]);
    }

    /**
     * Show A Poll.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id): Factory
    {
        $poll = Poll::where('id', '=', $id)->firstOrFail();

        return $this->viewFactory->make('Staff.poll.show', ['poll' => $poll]);
    }

    /**
     * Poll Add Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(): Factory
    {
        return $this->viewFactory->make('Staff.poll.create');
    }

    /**
     * Store A New Poll.
     *
     * @param  StorePoll  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePoll $request): RedirectResponse
    {
        $user = $request->user();

        $poll = $request->user() ? $user->polls()->create($request->all()) : Poll::create($request->all());

        $options = (new Collection($request->input('options')))->map(fn ($value) => new Option(['name' => $value]));
        $poll->options()->saveMany($options);

        $poll_url = hrefPoll($poll);

        $this->chat->systemMessage(
            sprintf('A new poll has been created [url=%s]%s[/url] vote on it now! :slight_smile:', $poll_url, $poll->title)
        );

        return $this->redirector->route('staff.polls.index')
            ->withSuccess('Your poll has been created.');
    }
}

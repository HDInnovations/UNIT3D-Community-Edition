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
use App\Models\Option;
use App\Models\Poll;
use App\Repositories\ChatRepository;

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
     * Display All Polls.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $polls = Poll::latest()->paginate(25);

        return view('Staff.poll.index', ['polls' => $polls]);
    }

    /**
     * Show A Poll.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $poll = Poll::where('id', '=', $id)->firstOrFail();

        return view('Staff.poll.show', ['poll' => $poll]);
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
     * Store A New Poll.
     *
     * @param StorePoll $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function store(StorePoll $request)
    {
        $user = $request->user();

        $poll = $request->user() ? $user->polls()->create($request->all()) : Poll::create($request->all());

        $options = collect($request->input('options'))->map(function ($value) {
            return new Option(['name' => $value]);
        });
        $poll->options()->saveMany($options);

        $poll_url = hrefPoll($poll);

        $this->chat->systemMessage(
            sprintf('A new poll has been created [url=%s]%s[/url] vote on it now! :slight_smile:', $poll_url, $poll->title)
        );

        return redirect()->route('staff.polls.index')
            ->withSuccess('Your poll has been created.');
    }

    /**
     * Poll Edit Form.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $poll = Poll::findOrFail($id);

        return view('Staff.poll.edit', ['poll' => $poll]);
    }

    /**
     * Update A New Poll.
     *
     * @param StorePoll $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function update(StorePoll $request, $id)
    {
        $poll = Poll::findOrFail($id);

        $poll->title = $request->input('title');

        if ($request->input('multiple_choice')) {
            $poll->multiple_choice = true;
        } else {
            $poll->multiple_choice = false;
        }

        // Remove the deleted options in poll
        $oldOptionIds = collect($poll->options)->map(function ($option) {
            return $option->id;
        })->all();

        $existingOldOptionIds = collect($request->input('option-id'))->map(function ($id) {
            return intval($id);
        })->all();

        $idsOfOptionToBeRemove = array_diff($oldOptionIds, $existingOldOptionIds);

        foreach ($idsOfOptionToBeRemove as $id) {
            $option = Option::findOrFail($id);
            $option->delete();
        }

        // Update existing options
        $existingOldOptionContents = collect($request->input('option-content'))->map(function ($content) {
            return strval($content);
        })->all();

        if (count($existingOldOptionContents) === count($existingOldOptionIds)) {
            $len = count($existingOldOptionContents);
            for ($i = 0; $i < $len; $i++) {
                $option = Option::findOrFail($existingOldOptionIds[$i]);
                $option->name = $existingOldOptionContents[$i];
                $option->save();
            }
        }

        // Insert new options
        $newOptions = collect($request->input('new-option-content'))->map(function ($content) {
            return new Option(['name' => $content]);
        });

        $poll->options()->saveMany($newOptions);

        // Last work from store()
        $poll_url = hrefPoll($poll);

        $this->chat->systemMessage(
            sprintf('A poll has been updated [url=%s]%s[/url] vote on it now! :slight_smile:', $poll_url, $poll->title)
        );

        $poll->save();

        return redirect()->route('staff.polls.index')
            ->withSuccess('Your poll has been edited.');
    }

    /**
     * Delete A Poll.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $poll = Poll::findOrFail($id);
        $poll->delete();

        return redirect()->route('staff.polls.index')
            ->withSuccess('Poll has successfully been deleted');
    }
}

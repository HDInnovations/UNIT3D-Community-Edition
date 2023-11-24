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

use App\Models\Forum;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Http\Request;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\Staff\ForumControllerTest
 */
class ForumController extends Controller
{
    /**
     * Show All Forums.
     */
    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('forum.index', [
            'categories' => Forum::query()
                ->with([
                    'forums' => fn ($query) => $query
                        ->whereRelation('permissions', [['show_forum', '=', 1], ['group_id', '=', $request->user()->group_id]]),
                    'forums.latestPoster' => fn ($query) => $query->withTrashed(),
                ])
                ->whereNull('parent_id')
                ->whereRelation('permissions', [['show_forum', '=', 1], ['group_id', '=', $request->user()->group_id]])
                ->orderBy('position')
                ->get(),
            'num_posts'  => Post::count(),
            'num_forums' => Forum::count(),
            'num_topics' => Topic::count(),
        ]);
    }

    /**
     * Show Forums And Topics Inside.
     */
    public function show(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        // Find the topic
        $forum = Forum::findOrFail($id);

        // Check if this is a category or forum
        if ($forum->parent_id === null) {
            return to_route('forums.categories.show', ['id' => $forum->id]);
        }

        // Check if the user has permission to view the forum
        if (!$forum->getPermission()->show_forum) {
            return to_route('forums.index')
                ->withErrors('You Do Not Have Access To This Forum!');
        }

        return view('forum.forum_topic.index', [
            'forum' => $forum,
        ]);
    }
}

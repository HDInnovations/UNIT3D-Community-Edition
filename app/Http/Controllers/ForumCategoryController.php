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
use App\Models\Topic;

/**
 * @see \Tests\Feature\Http\Controllers\ForumCategoryControllerTest
 */
class ForumCategoryController extends Controller
{
    /**
     * Show The Forum Category.
     */
    public function show(int $id): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
    {
        // Find the topic
        $forum = Forum::findOrFail($id);

        // Check if this is a category or forum
        if ($forum->parent_id != 0) {
            return to_route('forums.show', ['id' => $forum->id]);
        }

        // Check if the user has permission to view the forum
        if (! $forum->getPermission()->show_forum) {
            return to_route('forums.index')
                ->withErrors('You Do Not Have Access To This Category!');
        }

        // Fetch topics->posts in descending order
        // $topics = $forum->sub_topics()->latest('pinned')->latest('last_reply_at')->latest()->paginate(25);

        return view('forum.category_topic.index', [
            'forum' => $forum,
        ]);
    }
}

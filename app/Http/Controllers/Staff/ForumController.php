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
use App\Http\Requests\Staff\StoreForumRequest;
use App\Http\Requests\Staff\UpdateForumRequest;
use App\Models\Forum;
use App\Models\ForumCategory;
use App\Models\Group;
use App\Models\ForumPermission;
use Exception;
use Illuminate\Http\Request;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\Staff\ForumControllerTest
 */
class ForumController extends Controller
{
    /**
     * Show Forum Create Form.
     */
    public function create(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.forum.create', [
            'forumCategoryId' => $request->integer('forumCategoryId'),
            'categories'      => ForumCategory::orderBy('position')->get(),
            'groups'          => Group::orderBy('position')->get(),
        ]);
    }

    /**
     * Store A New Forum.
     */
    public function store(StoreForumRequest $request): \Illuminate\Http\RedirectResponse
    {
        $forum = Forum::create($request->validated('forum'));

        ForumPermission::upsert(
            array_map(fn ($item) => ['forum_id' => $forum->id] + $item, $request->validated('permissions')),
            ['forum_id', 'group_id']
        );

        return to_route('staff.forum_categories.index')
            ->withSuccess('Forum has been created successfully');
    }

    /**
     * Forum Edit Form.
     */
    public function edit(Forum $forum): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.forum.edit', [
            'categories' => ForumCategory::orderBy('position')->get(),
            'groups'     => Group::orderBy('position')->get(),
            'forum'      => $forum->load(['permissions', 'category']),
        ]);
    }

    /**
     * Edit A Forum.
     */
    public function update(UpdateForumRequest $request, Forum $forum): \Illuminate\Http\RedirectResponse
    {
        $forum->update($request->validated('forum'));

        ForumPermission::upsert(
            array_map(fn ($item) => ['forum_id' => $forum->id] + $item, $request->validated('permissions')),
            ['forum_id', 'group_id']
        );

        return to_route('staff.forum_categories.index')
            ->withSuccess('Forum has been edited successfully');
    }

    /**
     * Delete A Forum.
     *
     * @throws Exception
     */
    public function destroy(Forum $forum): \Illuminate\Http\RedirectResponse
    {
        $forum->delete();

        return to_route('staff.forum_categories.index')
            ->withSuccess('Forum has been deleted successfully');
    }
}

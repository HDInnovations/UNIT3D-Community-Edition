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
use App\Models\Forum;
use App\Models\Group;
use App\Models\Privilege;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\Staff\ForumControllerTest
 */
class ForumController extends Controller
{
    /**
     * Display All Forums.
     */
    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();
        \abort_unless($user->group->is_admin, 403);

        $categories = Forum::where('parent_id', '=', 0)->get()->sortBy('position');

        return \view('Staff.forum.index', ['categories' => $categories]);
    }

    /**
     * Show Forum Create Form.
     */
    public function create(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();
        \abort_unless($user->group->is_admin, 403);

        $categories = Forum::where('parent_id', '=', 0)->get();
        $groups = Group::all();

        return \view('Staff.forum.create', ['categories' => $categories, 'groups' => $groups]);
    }

    /**
     * Store A New Forum.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        \abort_unless($request->user()->hasPrivilegeTo('dashboard_can_forums'), 403);
        $groups = Group::all();

        $forum = new Forum();
        $forum->name = $request->input('title');
        $forum->position = $request->input('position');
        $forum->slug = Str::slug($request->input('title'));
        $forum->description = $request->input('description');
        $forum->parent_id = $request->input('parent_id');
        $forum->save();

        $showForum = Privilege::create(['slug'=> 'forum_'.$forum->slug.'_show_forum', 'name' =>'Forums: '.$forum->name.' - Show Forum'])->save();
        $readTopics = Privilege::create(['slug'=> 'forum_'.$forum->slug.'_read_topic', 'name' =>'Forums: '.$forum->name.' - Read Topics'])->save();
        $replyTopic = Privilege::create(['slug'=> 'forum_'.$forum->slug.'_reply_topic', 'name' =>'Forums: '.$forum->name.' - Reply To Topics'])->save();
        $createTopic = Privilege::create(['slug'=> 'forum_'.$forum->slug.'_start_topic', 'name' =>'Forums: '.$forum->name.' - Create Topics'])->save();

        if (! $request->user()->hasPrivilegeTo('forums_sudo')) {
            $request->user()->privilege()->attach($showForum);
            $request->user()->privilege()->attach($readTopics);
            $request->user()->privilege()->attach($replyTopic);
            $request->user()->privilege()->attach($createTopic);
        }

        return \to_route('staff.forums.index')
            ->withSuccess('Forum has been created successfully');
    }

    /**
     * Forum Edit Form.
     */
    public function edit(Request $request, int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();
        \abort_unless($user->group->is_admin, 403);

        $forum = Forum::findOrFail($id);
        $categories = Forum::where('parent_id', '=', 0)->get();
        $groups = Group::all();

        return \view('Staff.forum.edit', [
            'categories' => $categories,
            'groups'     => $groups,
            'forum'      => $forum,
        ]);
    }

    /**
     * Edit A Forum.
     */
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        \abort_unless($user->group->is_admin, 403);

        $forum = Forum::findOrFail($id);

        $forum->name = $request->input('title');
        $forum->position = $request->input('position');
        $forum->slug = Str::slug($request->input('title'));
        $forum->description = $request->input('description');
        $forum->parent_id = $request->input('forum_type') == 'category' ? 0 : $request->input('parent_id');
        $forum->save();

        return \to_route('staff.forums.index')
            ->withSuccess('Forum has been edited successfully');
    }

    /**
     * Delete A Forum.
     *
     * @throws \Exception
     */
    public function destroy(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        \abort_unless($request->user()->hasPrivilegeTo('dashboard_can_forums'), 403);

        // Forum to delete
        $forum = Forum::findOrFail($id);
        $showForum = Privilege::where('slug', 'forum_'.$forum->slug.'_show_forum')->firstOrFail();
        $readTopics = Privilege::where('slug', 'forum_'.$forum->slug.'_read_topic')->firstOrFail();
        $replyTopic = Privilege::where('slug', 'forum_'.$forum->slug.'_reply_topic')->firstOrFail();
        $createTopic = Privilege::where('slug', 'forum_'.$forum->slug.'_start_topic')->firstOrFail();

        $forum->delete();
        $showForum->delete();
        $readTopics->delete();
        $replyTopic->delete();
        $createTopic->delete();

        return \to_route('staff.forums.index')
            ->withSuccess('Forum has been deleted successfully');
    }
}

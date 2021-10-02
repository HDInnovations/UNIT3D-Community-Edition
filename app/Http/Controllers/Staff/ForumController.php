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
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $categories = Forum::where('parent_id', '=', 0)->get()->sortBy('position');

        return \view('Staff.forum.index', ['categories' => $categories]);
    }

    /**
     * Show Forum Create Form.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $categories = Forum::where('parent_id', '=', 0)->get();
        $groups = Group::all();

        return \view('Staff.forum.create', ['categories' => $categories, 'groups' => $groups]);
    }

    /**
     * Store A New Forum.
     *
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        \abort_unless($request->user()->hasPrivilegeTo('dashboard_can_forums'), 403);

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

        return \redirect()->route('staff.forums.index')
            ->withSuccess('Forum has been created successfully');
    }

    /**
     * Forum Edit Form.
     *
     * @param \App\Models\Forum $id
     */
    public function edit($id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
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
     *
     * @param \App\Models\Forum $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $forum = Forum::findOrFail($id);

        $forum->name = $request->input('title');
        $forum->position = $request->input('position');
        $forum->slug = Str::slug($request->input('title'));
        $forum->description = $request->input('description');
        $forum->parent_id = $request->input('forum_type') == 'category' ? 0 : $request->input('parent_id');
        $forum->save();

        return \redirect()->route('staff.forums.index')
            ->withSuccess('Forum has been edited successfully');
    }

    /**
     * Delete A Forum.
     *
     * @param \App\Models\Forum $id
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
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

        return \redirect()->route('staff.forums.index')
            ->withSuccess('Forum has been deleted successfully');
    }
}

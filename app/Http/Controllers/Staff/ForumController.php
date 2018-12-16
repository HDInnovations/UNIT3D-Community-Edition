<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use App\Forum;
use App\Group;
use App\Permission;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ForumController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * ForumController Constructor.
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Show Forums.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $categories = Forum::where('parent_id', '=', 0)->get();

        return view('Staff.forum.index', ['categories' => $categories]);
    }

    /**
     * Forum Add Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addForm()
    {
        $categories = Forum::where('parent_id', '=', 0)->get();
        $groups = Group::all();

        return view('Staff.forum.add', ['categories' => $categories, 'groups' => $groups]);
    }

    /**
     * Add A Forum.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        $groups = Group::all();

        $forum = new Forum();
        $forum->name = $request->input('title');
        $forum->position = $request->input('position');
        $forum->slug = str_slug($request->input('title'));
        $forum->description = $request->input('description');
        $forum->parent_id = $request->input('parent_id');
        $forum->save();

        // Permissions
        foreach ($groups as $k => $group) {
            $perm = Permission::whereRaw('forum_id = ? AND group_id = ?', [$forum->id, $group->id])->first();
            if ($perm == null) {
                $perm = new Permission();
            }
            $perm->forum_id = $forum->id;
            $perm->group_id = $group->id;
            if (array_key_exists($group->id, $request->input('permissions'))) {
                $perm->show_forum = (isset($request->input('permissions')[$group->id]['show_forum'])) ? true : false;
                $perm->read_topic = (isset($request->input('permissions')[$group->id]['read_topic'])) ? true : false;
                $perm->reply_topic = (isset($request->input('permissions')[$group->id]['reply_topic'])) ? true : false;
                $perm->start_topic = (isset($request->input('permissions')[$group->id]['start_topic'])) ? true : false;
            } else {
                $perm->show_forum = false;
                $perm->read_topic = false;
                $perm->reply_topic = false;
                $perm->start_topic = false;
            }
            $perm->save();
        }

        return redirect()->route('staff_forum_index')
                ->with($this->toastr->success('Forum has been created successfully', 'Yay!', ['options']));
    }

    /**
     * Forum Edit Form.
     *
     * @param $slug
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editForm($slug, $id)
    {
        $forum = Forum::findOrFail($id);
        $categories = Forum::where('parent_id', '=', 0)->get();
        $groups = Group::all();

        return view('Staff.forum.edit', [
            'categories' => $categories,
            'groups'     => $groups,
            'forum'      => $forum,
        ]);
    }

    /**
     * Edit A Forum.
     *
     * @param \Illuminate\Http\Request $request
     * @param $slug
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $slug, $id)
    {
        $forum = Forum::findOrFail($id);
        $groups = Group::all();

        $forum->name = $request->input('title');
        $forum->position = $request->input('position');
        $forum->slug = str_slug($request->input('title'));
        $forum->description = $request->input('description');
        if ($request->input('forum_type') == 'category') {
            $forum->parent_id = 0;
        } else {
            $forum->parent_id = $request->input('parent_id');
        }
        $forum->save();

        // Permissions
        foreach ($groups as $k => $group) {
            $perm = Permission::whereRaw('forum_id = ? AND group_id = ?', [$forum->id, $group->id])->first();
            if ($perm == null) {
                $perm = new Permission();
            }
            $perm->forum_id = $forum->id;
            $perm->group_id = $group->id;
            if (array_key_exists($group->id, $request->input('permissions'))) {
                $perm->show_forum = (isset($request->input('permissions')[$group->id]['show_forum'])) ? true : false;
                $perm->read_topic = (isset($request->input('permissions')[$group->id]['read_topic'])) ? true : false;
                $perm->reply_topic = (isset($request->input('permissions')[$group->id]['reply_topic'])) ? true : false;
                $perm->start_topic = (isset($request->input('permissions')[$group->id]['start_topic'])) ? true : false;
            } else {
                $perm->show_forum = false;
                $perm->read_topic = false;
                $perm->reply_topic = false;
                $perm->start_topic = false;
            }
            $perm->save();
        }

        return redirect()->route('staff_forum_index')
                ->with($this->toastr->success('Forum has been edited successfully', 'Yay!', ['options']));
    }

    /**
     * Delete A Forum.
     *
     * @param $slug
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function delete($slug, $id)
    {
        // Forum to delete
        $forum = Forum::findOrFail($id);

        $permissions = Permission::where('forum_id', '=', $forum->id)->get();
        foreach ($permissions as $p) {
            $p->delete();
        }
        unset($permissions);

        if ($forum->parent_id == 0) {
            $category = $forum;
            $permissions = Permission::where('forum_id', '=', $category->id)->get();
            foreach ($permissions as $p) {
                $p->delete();
            }

            $forums = $category->getForumsInCategory();
            foreach ($forums as $forum) {
                $permissions = Permission::where('forum_id', '=', $forum->id)->get();
                foreach ($permissions as $p) {
                    $p->delete();
                }

                foreach ($forum->topics as $t) {
                    foreach ($t->posts as $p) {
                        $p->delete();
                    }
                    $t->delete();
                }
                $forum->delete();
            }
            $category->delete();
        } else {
            $permissions = Permission::where('forum_id', '=', $forum->id)->get();
            foreach ($permissions as $p) {
                $p->delete();
            }
            foreach ($forum->topics as $t) {
                foreach ($t->posts as $p) {
                    $p->delete();
                }
                $t->delete();
            }
            $forum->delete();
        }

        return redirect()->route('staff_forum_index')
            ->with($this->toastr->success('Forum has been deleted successfully', 'Yay!', ['options']));
    }
}

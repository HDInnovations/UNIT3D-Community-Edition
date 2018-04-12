<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Forum;
use App\Group;
use App\Permission;
use \Toastr;

class ForumController extends Controller
{

    /**
     * Show Forums
     *
     */
    public function index()
    {
        $categories = Forum::where('parent_id', 0)->get();

        return view('Staff.forum.index', ['categories' => $categories]);
    }

    /**
     * Add A Forum
     *
     */
    public function add(Request $request)
    {
        $categories = Forum::where('parent_id', 0)->get();
        $groups = Group::all();
        if ($request->isMethod('POST')) {
            $parentForum = Forum::findOrFail($request->input('parent_id'));
            $forum = new Forum();
            $forum->name = $request->input('title');
            $forum->position = $request->input('position');
            $forum->slug = str_slug($request->input('title'));
            $forum->description = $request->input('description');
            $forum->parent_id = ($request->input('forum_type') == 'category') ? 0 : $parentForum->id;
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

            return redirect()->route('staff_forum_index')->with(Toastr::success('Forum has been created successfully', 'Yay!', ['options']));
        }
        return view('Staff.forum.add', ['categories' => $categories, 'groups' => $groups]);
    }

    /**
     * Edit A Forum
     *
     *
     */
    public function edit(Request $request, $slug, $id)
    {
        $categories = Forum::where('parent_id', 0)->get();
        $groups = Group::all();
        $forum = Forum::findOrFail($id);
        if ($request->isMethod('POST')) {
            $forum->name = $request->input('title');
            $forum->position = $request->input('position');
            $forum->slug = str_slug($request->input('title'));
            $forum->description = $request->input('description');
            $forum->parent_id = ($request->input('forum_type') == 'category') ? 0 : $request->input('parent_id');
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

            return redirect()->route('staff_forum_index')->with(Toastr::success('Forum has been edited successfully', 'Yay!', ['options']));
        }
        return view('Staff.forum.edit', ['categories' => $categories, 'groups' => $groups, 'forum' => $forum]);
    }

    /**
     * Delete A Forum
     *
     *
     */
    public function delete($slug, $id)
    {
        // Forum to delete
        $forum = Forum::findOrFail($id);

        $permissions = Permission::where('forum_id', $forum->id)->get();
        foreach ($permissions as $p) {
            $p->delete();
        }
        unset($permissions);

        if ($forum->parent_id == 0) {
            $category = $forum;
            $permissions = Permission::where('forum_id', $category->id)->get();
            foreach ($permissions as $p) {
                $p->delete();
            }

            $forums = $category->getForumsInCategory();
            foreach ($forums as $forum) {
                $permissions = Permission::where('forum_id', $forum->id)->get();
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
            $permissions = Permission::where('forum_id', $forum->id)->get();
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
        return redirect()->route('staff_forum_index')->with(Toastr::success('Forum has been deleted successfully', 'Yay!', ['options']));
    }
}

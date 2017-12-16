<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use App\Forum;
use App\Group;
use App\Http\Controllers\Controller;
use App\Permission;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class ForumController extends Controller
{

    /**
     * Affiche la page d'index d'administration du forum
     *
     */
    public function index()
    {
        $categories = Forum::where('parent_id', '=', 0)->get();

        return view('Staff.forum.index', ['categories' => $categories]);
    }

    /**
     * Ajoute une catégorie / un forum
     *
     */
    public function add()
    {
        $categories = Forum::where('parent_id', '=', 0)->get();
        $groups = Group::all();
        if (Request::isMethod('post')) {
            $parentForum = Forum::findOrFail(Request::get('parent_id'));
            $forum = new Forum();
            $forum->name = Request::get('title');
            $forum->position = Request::get('position');
            $forum->slug = str_slug(Request::get('title'));
            $forum->description = Request::get('description');
            $forum->parent_id = (Request::get('forum_type') == 'category') ? 0 : $parentForum->id;
            $forum->save();

            // Sauvegarde les permission<s></s>
            foreach ($groups as $k => $group) {
                $perm = Permission::whereRaw('forum_id = ? AND group_id = ?', [$forum->id, $group->id])->first();
                if ($perm == null) {
                    $perm = new Permission();
                }
                $perm->forum_id = $forum->id;
                $perm->group_id = $group->id;
                if (array_key_exists($group->id, Request::get('permissions'))) {
                    $perm->show_forum = (isset(Request::get('permissions')[$group->id]['show_forum'])) ? true : false;
                    $perm->read_topic = (isset(Request::get('permissions')[$group->id]['read_topic'])) ? true : false;
                    $perm->reply_topic = (isset(Request::get('permissions')[$group->id]['reply_topic'])) ? true : false;
                    $perm->start_topic = (isset(Request::get('permissions')[$group->id]['start_topic'])) ? true : false;
                    $perm->upload = (isset(Request::get('permissions')[$group->id]['upload'])) ? true : false;
                    $perm->download = (isset(Request::get('permissions')[$group->id]['download'])) ? true : false;
                } else {
                    $perm->show_forum = false;
                    $perm->read_topic = false;
                    $perm->reply_topic = false;
                    $perm->start_topic = false;
                    $perm->upload = false;
                    $perm->download = false;
                }
                $perm->save();
            }

            return Redirect::route('staff_forum_index');
        }
        return view('Staff.forum.add', ['categories' => $categories, 'groups' => $groups]);
    }

    /**
     * Edite le forum
     *
     *
     */
    public function edit($slug, $id)
    {
        $categories = Forum::where('parent_id', '=', 0)->get();
        $groups = Group::all();
        $forum = Forum::findOrFail($id);
        if (Request::isMethod('post')) {
            $forum->name = Request::get('title');
            $forum->position = Request::get('position');
            $forum->slug = str_slug(Request::get('title'));
            $forum->description = Request::get('description');
            //$forum->parent_id = (Request::get('forum_type') == 'category') ? 0 : Request::get('parent_id'); // Non changé depuis la création
            $forum->parent_id = Request::get('parent_id');
            $forum->save();

            // Sauvegarde des permissions dans la DB
            foreach ($groups as $k => $group) {
                $perm = Permission::whereRaw('forum_id = ? AND group_id = ?', [$forum->id, $group->id])->first();
                if ($perm == null) {
                    $perm = new Permission();
                }
                $perm->forum_id = $forum->id;
                $perm->group_id = $group->id;
                if (array_key_exists($group->id, Request::get('permissions'))) {
                    $perm->show_forum = (isset(Request::get('permissions')[$group->id]['show_forum'])) ? true : false;
                    $perm->read_topic = (isset(Request::get('permissions')[$group->id]['read_topic'])) ? true : false;
                    $perm->reply_topic = (isset(Request::get('permissions')[$group->id]['reply_topic'])) ? true : false;
                    $perm->start_topic = (isset(Request::get('permissions')[$group->id]['start_topic'])) ? true : false;
                    $perm->upload = (isset(Request::get('permissions')[$group->id]['upload'])) ? true : false;
                    $perm->download = (isset(Request::get('permissions')[$group->id]['download'])) ? true : false;
                } else {
                    $perm->show_forum = false;
                    $perm->read_topic = false;
                    $perm->reply_topic = false;
                    $perm->start_topic = false;
                    $perm->upload = false;
                    $perm->download = false;
                }
                $perm->save();
            }

            return Redirect::route('staff_forum_index');
        }
        return view('Staff.forum.edit', ['categories' => $categories, 'groups' => $groups, 'forum' => $forum]);
    }

    /**
     * Supprime un forum / une catégorie ainsi que les topics et sous-forums
     *
     *
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
        return Redirect::route('staff_forum_index');
    }
}

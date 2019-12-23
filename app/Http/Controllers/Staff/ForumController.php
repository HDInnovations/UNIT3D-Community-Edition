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

use Illuminate\Routing\Redirector;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\Group;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class ForumController extends Controller
{
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private $viewFactory;
    /**
     * @var \Illuminate\Routing\Redirector
     */
    private $redirector;
    public function __construct(Factory $viewFactory, Redirector $redirector)
    {
        $this->viewFactory = $viewFactory;
        $this->redirector = $redirector;
    }
    /**
     * Display All Forums.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(): Factory
    {
        $categories = Forum::where('parent_id', '=', 0)->get()->sortBy('position');

        return $this->viewFactory->make('Staff.forum.index', ['categories' => $categories]);
    }

    /**
     * Show Forum Create Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(): Factory
    {
        $categories = Forum::where('parent_id', '=', 0)->get();
        $groups = Group::all();

        return $this->viewFactory->make('Staff.forum.create', ['categories' => $categories, 'groups' => $groups]);
    }

    /**
     * Store A New Forum.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $groups = Group::all();

        $forum = new Forum();
        $forum->name = $request->input('title');
        $forum->position = $request->input('position');
        $forum->slug = Str::slug($request->input('title'));
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
                $perm->show_forum = isset($request->input('permissions')[$group->id]['show_forum']);
                $perm->read_topic = isset($request->input('permissions')[$group->id]['read_topic']);
                $perm->reply_topic = isset($request->input('permissions')[$group->id]['reply_topic']);
                $perm->start_topic = isset($request->input('permissions')[$group->id]['start_topic']);
            } else {
                $perm->show_forum = false;
                $perm->read_topic = false;
                $perm->reply_topic = false;
                $perm->start_topic = false;
            }
            $perm->save();
        }

        return $this->redirector->route('staff.forums.index')
            ->withSuccess('Forum has been created successfully');
    }

    /**
     * Forum Edit Form.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id): Factory
    {
        $forum = Forum::findOrFail($id);
        $categories = Forum::where('parent_id', '=', 0)->get();
        $groups = Group::all();

        return $this->viewFactory->make('Staff.forum.edit', [
            'categories' => $categories,
            'groups'     => $groups,
            'forum'      => $forum,
        ]);
    }

    /**
     * Edit A Forum.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param                            $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $forum = Forum::findOrFail($id);
        $groups = Group::all();

        $forum->name = $request->input('title');
        $forum->position = $request->input('position');
        $forum->slug = Str::slug($request->input('title'));
        $forum->description = $request->input('description');
        $forum->parent_id = $request->input('forum_type') == 'category' ? 0 : $request->input('parent_id');
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
                $perm->show_forum = isset($request->input('permissions')[$group->id]['show_forum']);
                $perm->read_topic = isset($request->input('permissions')[$group->id]['read_topic']);
                $perm->reply_topic = isset($request->input('permissions')[$group->id]['reply_topic']);
                $perm->start_topic = isset($request->input('permissions')[$group->id]['start_topic']);
            } else {
                $perm->show_forum = false;
                $perm->read_topic = false;
                $perm->reply_topic = false;
                $perm->start_topic = false;
            }
            $perm->save();
        }

        return $this->redirector->route('staff.forums.index')
            ->withSuccess('Forum has been edited successfully');
    }

    /**
     * Delete A Forum.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function destroy($id): RedirectResponse
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

        return $this->redirector->route('staff.forums.index')
            ->withSuccess('Forum has been deleted successfully');
    }
}

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

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Torrent;
use App\Models\Category;
use App\Models\PersonalFreeleech;

class CategoryController extends Controller
{
    /**
     * Show Categories.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function categories()
    {
        $categories = Category::withCount('torrents')->get()->sortBy('position');

        return view('category.categories', ['categories' => $categories]);
    }

    /**
     * Show All Torrents Within A Category.
     *
     * @param $slug
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function category(Request $request, $slug, $id)
    {
        $user = $request->user();
        $category = Category::select(['id', 'name', 'slug'])->findOrFail($id);
        $torrents = Torrent::with(['user', 'category'])->withCount(['thanks', 'comments'])->where('category_id', '=', $id)->orderBy('sticky', 'desc')->latest()->paginate(25);
        $personal_freeleech = PersonalFreeleech::where('user_id', '=', $user->id)->first();

        return view('category.category', [
            'torrents'           => $torrents,
            'user'               => $user,
            'category'           => $category,
            'personal_freeleech' => $personal_freeleech,
        ]);
    }
}

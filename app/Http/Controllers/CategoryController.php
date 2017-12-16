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

namespace App\Http\Controllers;

use App\Category;

use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{

    /**
     * Displays torrents by category
     *
     * @access public
     * @param $slug
     * @param $id
     * @return category.category View
     */
    public function category($slug, $id)
    {
        $user = Auth::user();
        $category = Category::findOrFail($id);
        $torrents = $category->torrents()->orderBy('created_at', 'DESC')->paginate(20);

        return view('category.category', ['torrents' => $torrents, 'user' => $user, 'category' => $category, 'categories' => Category::all()]);
    }

    /**
     * Display category list
     *
     * @access public
     * @return category.categories View
     */
    public function categories()
    {
        $categories = Category::all();

        return view('category.categories', ['categories' => $categories]);
    }

}

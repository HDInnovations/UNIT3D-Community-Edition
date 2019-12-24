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

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\PersonalFreeleech;
use App\Models\Torrent;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;

final class CategoryController extends Controller
{
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private Factory $viewFactory;

    public function __construct(Factory $viewFactory)
    {
        $this->viewFactory = $viewFactory;
    }

    /**
     * Display All Categories.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(): Factory
    {
        $categories = Category::withCount('torrents')->get()->sortBy('position');

        return $this->viewFactory->make('category.index', ['categories' => $categories]);
    }

    /**
     * Show A Category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param                            $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $id): Factory
    {
        $user = $request->user();
        $category = Category::select(['id', 'name'])->findOrFail($id);
        $torrents = Torrent::with(['user', 'category'])->withCount(['thanks', 'comments'])->where('category_id', '=', $id)->orderBy('sticky', 'desc')->latest()->paginate(25);
        $personal_freeleech = PersonalFreeleech::where('user_id', '=', $user->id)->first();

        return $this->viewFactory->make('category.show', [
            'torrents'           => $torrents,
            'user'               => $user,
            'category'           => $category,
            'personal_freeleech' => $personal_freeleech,
        ]);
    }
}

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

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Torrent;
use App\Models\User;
use App\Models\Wish;
use App\Services\Tmdb\Client\Movie;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use JsonException;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\WishControllerTest
 */
class WishController extends Controller
{
    /**
     * Get A Users Wishlist.
     */
    public function index(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        abort_unless($request->user()->group->is_modo || $request->user()->is($user), 403);

        return view('user.wish.index', [
            'user'   => $user,
            'wishes' => $user->wishes()->latest()->paginate(25),
            'route'  => 'wish',
        ]);
    }

    /**
     * Add New Wish.
     *
     * @throws JsonException
     */
    public function store(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user), 403);

        $request->validate([
            'tmdb' => [
                'required',
                'integer',
                'not_in:0',
                Rule::unique('wishes')->where(fn (Builder $query) => $query->where('user_id', '=', $user->id)),
            ],
            ''
        ]);

        $meta = (new Movie($request->tmdb))->data;

        if ($meta === null) {
            return to_route('users.wishes.index', ['user' => $user])
                ->withErrors('TMDM Bad Request!');
        }

        $torrent = Torrent::query()
            ->where('tmdb', '=', $request->tmdb)
            ->whereIn('category_id', Category::select('id')->where('movie_meta', '=', 1))
            ->where('seeders', '>', 0)
            ->where('status', '=', 1)
            ->first();

        Wish::create([
            'title'   => $meta['title'].' ('.$meta['release_date'].')',
            'type'    => 'Movie',
            'tmdb'    => $request->tmdb,
            'source'  => $torrent === null ? Wish::find($request->integer('tmdb'))?->source : route('torrents.show', $torrent->id),
            'user_id' => $user->id,
        ]);

        return to_route('users.wishes.index', ['user' => $user])
            ->withSuccess('Wish Successfully Added!');
    }

    /**
     * Delete A Wish.
     */
    public function destroy(Request $request, User $user, Wish $wish): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user) || $request->user()->group->is_modo, 403);

        $wish->delete();

        return to_route('users.wishes.index', ['user' => $user])
            ->withSuccess('Wish Successfully Removed!');
    }
}

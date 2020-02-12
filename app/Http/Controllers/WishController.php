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

namespace App\Http\Controllers;

use App\Interfaces\WishInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WishController extends Controller
{
    /**
     * @var WishInterface
     */
    private $wish;

    /**
     * WishController Constructor.
     *
     * @param WishInterface $wish
     */
    public function __construct(WishInterface $wish)
    {
        $this->wish = $wish;
    }

    /**
     * Get A Users Wishlist.
     *
     * @param Request $request
     * @param $username
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, $username)
    {
        $user = User::with('wishes')->where('username', '=', $username)->firstOrFail();

        abort_unless(($request->user()->group->is_modo || $request->user()->id == $user->id), 403);

        $wishes = $user->wishes()->latest()->paginate(25);

        return view('user.wishlist', [
            'user'               => $user,
            'wishes'             => $wishes,
            'route'              => 'wish',
        ]);
    }

    /**
     * Add New Wish.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $imdb = Str::startsWith($request->get('imdb'), 'tt') ? $request->get('imdb') : 'tt'.$request->get('imdb');

        if ($this->wish->exists($user->id, $imdb)) {
            return redirect()
                ->route('wishes.index', ['id' => $uid])
                ->withErrors('Wish already exists!');
        }

        $omdb = $this->wish->omdbRequest($imdb);
        if ($omdb === null || $omdb === false) {
            return redirect()
                ->route('wishes.index', ['id' => $uid])
                ->withErrors('IMDB Bad Request!');
        }

        $source = $this->wish->getSource($imdb);

        $this->wish->create([
            'title'   => $omdb['Title'].' ('.$omdb['Year'].')',
            'type'    => $omdb['Type'],
            'imdb'    => $imdb,
            'source'  => $source,
            'user_id' => $user->id,
        ]);

        return redirect()
            ->route('wishes.index', ['username' => $user->username])
            ->withSuccess('Wish Successfully Added!');
    }

    /**
     * Delete A Wish.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $this->wish->delete($id);

        return redirect()
            ->route('wishes.index', ['username' => $user->username])
            ->withSuccess('Wish Successfully Removed!');
    }
}

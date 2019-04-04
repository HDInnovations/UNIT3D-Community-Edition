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
 * @author     Poppabear
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\WishInterface;

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
     * Get Wish List.
     *
     * @param $uid
     *
     * @return void
     */
    public function index($uid)
    {
    }

    /**
     * Add New Wish.
     *
     * @param \Illuminate\Http\Request $request
     * @param $uid
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $uid)
    {
        $imdb = starts_with($request->get('imdb'), 'tt') ? $request->get('imdb') : 'tt'.$request->get('imdb');

        if ($this->wish->exists($uid, $imdb)) {
            return redirect()
                ->route('user_wishlist', ['slug' => auth()->user()->slug, 'id' => $uid])
                ->withErrors('Wish already exists!');
        }

        $omdb = $this->wish->omdbRequest($imdb);
        if ($omdb === null || $omdb === false) {
            return redirect()
                ->route('user_wishlist', ['slug' => auth()->user()->slug, 'id' => $uid])
                ->withErrors('IMDB Bad Request!');
        }

        $source = $this->wish->getSource($imdb);

        $this->wish->create([
            'title'   => $omdb['Title'].' ('.$omdb['Year'].')',
            'type'    => $omdb['Type'],
            'imdb'    => $imdb,
            'source'  => $source,
            'user_id' => $uid,
        ]);

        return redirect()
            ->route('user_wishlist', ['slug' => auth()->user()->slug, 'id' => $uid])
            ->withSuccess('Wish Successfully Added!');
    }

    /**
     * Delete A Wish.
     *
     * @param $uid
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($uid, $id)
    {
        $this->wish->delete($id);

        return redirect()
            ->route('user_wishlist', ['slug' => auth()->user()->slug, 'id' => $uid])
            ->withSuccess('Wish Successfully Removed!');
    }
}

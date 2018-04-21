<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Poppabear
 */

namespace App\Http\Controllers;

use App\Repositories\WishInterface;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;

class WishController extends Controller
{
    /**
     * @var WishInterface
     */
    private $wish;

    /**
     * todo: create interface for this class and register it to the service container.
     * Then we can inject the interface.
     *
     * @var Toastr
     */
    private $toastr;

    /**
     * WishController constructor.
     * @param WishInterface $wish
     * @param Toastr $toastr
     */
    public function __construct(WishInterface $wish, Toastr $toastr)
    {
        $this->wish = $wish;
        $this->toastr = $toastr;
    }

    /**
     * @param $uid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($uid)
    {
        $wishes = $this->wish->getUserWishes($uid);

        return view('user.wishlist', ['wishes' => $wishes]);
    }

    /**
     * @param Request $request
     * @param $uid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $uid)
    {
        $imdb = starts_with($request->get('imdb'), 'tt') ? $request->get('imdb') : 'tt'.$request->get('imdb');
        $type = $request->get('type');

        if ($this->wish->exists($uid, $imdb)) {
            return redirect()
                ->route('wishlist', ['id' => $uid])
                ->with($this->toastr->error('Wish already exists!', 'Whoops!', ['options']));
        }

        $omdb = $this->wish->omdbRequest($imdb, $type);
        if($omdb === null || $omdb === false) {
            return redirect()
                ->route('wishlist', ['id' => $uid])
                ->with($this->toastr->error('IMDB Bad Request!', 'Whoops!', ['options']));
        };

        $source = $this->wish->getSource($imdb);

        $this->wish->create([
            'title' => $omdb['Title'] . ' (' . $omdb['Year'] . ')',
            'type' => $type,
            'imdb' => $imdb,
            'source' => $source,
            'user_id' => $uid
        ]);

        return redirect()
            ->route('wishlist', ['id' => $uid])
            ->with($this->toastr->success('Wish Successfully Added!', 'Yay!', ['options']));
    }

    /**
     * @param $uid
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($uid, $id)
    {
        $this->wish->delete($id);

        return redirect()
            ->route('wishlist', ['id' => $uid])
            ->with($this->toastr->success('Wish Successfully Removed!', 'Yay!', ['options']));
    }
}

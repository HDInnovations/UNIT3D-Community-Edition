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

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Seedbox;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\SeedboxControllerTest
 */
class SeedboxController extends Controller
{
    /**
     * Display All Registered Seedboxes.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $seedboxes = Seedbox::with('user')->latest()->paginate(50);

        return \view('Staff.seedbox.index', ['seedboxes' => $seedboxes]);
    }

    /**
     * Delete A Registered Seedbox.
     *
     * @throws \Exception
     */
    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        Seedbox::findOrFail($id)->delete();

        return \to_route('staff.seedboxes.index')
            ->withSuccess('Seedbox Record Has Successfully Been Deleted');
    }
}

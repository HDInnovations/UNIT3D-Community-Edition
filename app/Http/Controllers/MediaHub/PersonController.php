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

namespace App\Http\Controllers\MediaHub;

use App\Http\Controllers\Controller;
use App\Models\Person;

class PersonController extends Controller
{
    /**
     * Display All Persons.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('mediahub.person.index');
    }

    /**
     * Show A Person.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $details = Person::findOrFail($id);
        $credits = Person::with(['tv', 'season', 'episode', 'movie'])->findOrFail($id);

        return view('mediahub.person.show', ['credits' => $credits, 'details' => $details]);
    }
}

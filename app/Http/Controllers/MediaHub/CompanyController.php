<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\MediaHub;

use App\Models\Company;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    /**
     * Display All Companies.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('mediahub.company.index');
    }

    /**
     * Show A Company.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $company = Company::with('tv', 'movie')->findOrFail($id);

        return view('mediahub.company.show', [
            'company' => $company,
        ]);
    }
}
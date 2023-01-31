<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreDistributorRequest;
use App\Http\Requests\Staff\UpdateDistributorRequest;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DistributorController extends Controller
{
    /**
     * Display All Distributors.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $distributors = Distributor::all()->sortBy('position');

        return \view('Staff.distributor.index', ['distributors' => $distributors]);
    }

    /**
     * Show Distributor Create Form.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('Staff.distributor.create');
    }

    /**
     * Store A New Distributor.
     */
    public function store(StoreDistributorRequest $request): \Illuminate\Http\RedirectResponse
    {
        Distributor::create($request->validated());

        return \to_route('staff.distributors.index')
                ->withSuccess('Distributor Successfully Added');
    }

    /**
     * Distributor Edit Form.
     */
    public function edit(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $distributor = Distributor::findOrFail($id);

        return \view('Staff.distributor.edit', ['distributor' => $distributor]);
    }

    /**
     * Edit A Distributor.
     */
    public function update(UpdateDistributorRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        Distributor::where('id', '=', $id)->update($request->validated());

        return \to_route('staff.distributors.index')
                ->withSuccess('Distributor Successfully Modified');
    }

    /**
     * Delete Edit Form.
     */
    public function delete(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $distributors = Distributor::all()->sortBy('position');
        $distributor = Distributor::findOrFail($id);

        return \view('Staff.distributor.delete', ['distributors' => $distributors, 'distributor' => $distributor]);
    }

    /**
     * Destroy A Distributor.
     *
     * @throws \Exception
     */
    public function destroy(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $distributor = Distributor::findOrFail($id);

        $validated = $request->validate([
            'distributor_id' => [
                'required',
                'exists:distributors,id',
                Rule::notIn([$distributor->id]),
            ],
        ]);
        $distributor->torrents()->update($validated);
        $distributor->delete();

        return \to_route('staff.distributors.index')
            ->withSuccess('Distributor Successfully Deleted');
    }
}

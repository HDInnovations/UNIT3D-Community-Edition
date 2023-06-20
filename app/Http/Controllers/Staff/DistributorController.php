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
use App\Http\Requests\Staff\DestroyDistributorRequest;
use App\Http\Requests\Staff\StoreDistributorRequest;
use App\Http\Requests\Staff\UpdateDistributorRequest;
use App\Models\Distributor;
use Exception;

class DistributorController extends Controller
{
    /**
     * Display All Distributors.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.distributor.index', [
            'distributors' => Distributor::orderBy('position')->get(),
        ]);
    }

    /**
     * Show Distributor Create Form.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.distributor.create');
    }

    /**
     * Store A New Distributor.
     */
    public function store(StoreDistributorRequest $request): \Illuminate\Http\RedirectResponse
    {
        Distributor::create($request->validated());

        return to_route('staff.distributors.index')
            ->withSuccess('Distributor Successfully Added');
    }

    /**
     * Distributor Edit Form.
     */
    public function edit(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.distributor.edit', [
            'distributor' => Distributor::findOrFail($id),
        ]);
    }

    /**
     * Edit A Distributor.
     */
    public function update(UpdateDistributorRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        Distributor::findOrFail($id)->update($request->validated());

        return to_route('staff.distributors.index')
            ->withSuccess('Distributor Successfully Modified');
    }

    /**
     * Delete Edit Form.
     */
    public function delete(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.distributor.delete', [
            'distributors' => Distributor::orderBy('position')->get(),
            'distributor'  => Distributor::findOrFail($id),
        ]);
    }

    /**
     * Destroy A Distributor.
     *
     * @throws Exception
     */
    public function destroy(DestroyDistributorRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $distributor = Distributor::findOrFail($id);
        $distributor->torrents()->update($request->validated());
        $distributor->delete();

        return to_route('staff.distributors.index')
            ->withSuccess('Distributor Successfully Deleted');
    }
}

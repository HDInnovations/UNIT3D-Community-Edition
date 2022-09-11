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
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $distributor = new Distributor();
        $distributor->name = $request->input('name');
        $distributor->slug = Str::slug($distributor->name);
        $distributor->position = $request->input('position');

        $v = \validator($distributor->toArray(), [
            'name'     => 'required|unique:distributors,name',
            'slug'     => 'required',
            'position' => 'required',
        ]);

        if ($v->fails()) {
            return \to_route('staff.distributors.index')
                ->withErrors($v->errors());
        }

        $distributor->save();

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
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $distributor = Distributor::findOrFail($id);
        $distributor->name = $request->input('name');
        $distributor->slug = Str::slug($distributor->name);
        $distributor->position = $request->input('position');

        $v = \validator($distributor->toArray(), [
            'name'     => 'required',
            'slug'     => 'required',
            'position' => 'required',
        ]);

        if ($v->fails()) {
            return \to_route('staff.distributors.index')
                ->withErrors($v->errors());
        }

        $distributor->save();

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

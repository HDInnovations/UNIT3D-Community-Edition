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
use App\Http\Requests\Staff\StoreTypeRequest;
use App\Http\Requests\Staff\UpdateTypeRequest;
use App\Models\Type;

/**
 * @see \Tests\Feature\Http\Controllers\Staff\TypeControllerTest
 */
class TypeController extends Controller
{
    /**
     * Display All Types.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $types = Type::all()->sortBy('position');

        return \view('Staff.type.index', ['types' => $types]);
    }

    /**
     * Show Type Create Form.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('Staff.type.create');
    }

    /**
     * Store A New Type.
     */
    public function store(StoreTypeRequest $request): \Illuminate\Http\RedirectResponse
    {
        Type::create($request->validated());

        return \to_route('staff.types.index')
            ->withSuccess('Type Successfully Added');
    }

    /**
     * Type Edit Form.
     */
    public function edit(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $type = Type::findOrFail($id);

        return \view('Staff.type.edit', ['type' => $type]);
    }

    /**
     * Edit A Type.
     */
    public function update(UpdateTypeRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        Type::where('id', '=', $id)->update($request->validated());

        return \to_route('staff.types.index')
            ->withSuccess('Type Successfully Modified');
    }

    /**
     * Delete A Type.
     *
     * @throws \Exception
     */
    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        $type = Type::findOrFail($id);
        $type->delete();

        return \to_route('staff.types.index')
            ->withSuccess('Type Successfully Deleted');
    }
}

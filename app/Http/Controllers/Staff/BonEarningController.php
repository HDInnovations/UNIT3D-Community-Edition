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
use App\Http\Requests\Staff\StoreBonEarningRequest;
use App\Http\Requests\Staff\UpdateBonEarningRequest;
use App\Models\BonEarning;
use App\Models\BonEarningCondition;
use Exception;
use Illuminate\Support\Arr;

class BonEarningController extends Controller
{
    /**
     * Display All Bon Earnings.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.bon_earning.index', [
            'bonEarnings' => BonEarning::with('conditions')->orderBy('position')->get(),
        ]);
    }

    /**
     * Show Form For Creating A New Bon Earning.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.bon_earning.create');
    }

    /**
     * Store A Bon Earning.
     */
    public function store(StoreBonEarningRequest $request): \Illuminate\Http\RedirectResponse
    {
        $bonEarning = BonEarning::create($request->safe()->only(['name', 'description', 'position', 'variable', 'multiplier', 'operation']));
        $bonEarning->conditions()->createMany($request->safe()->only(['conditions'])['conditions'] ?? []);

        return to_route('staff.bon_earnings.index')
            ->withSuccess('Bon Exchange Successfully Added');
    }

    /**
     * Bon Earning Edit Form.
     */
    public function edit(BonEarning $bonEarning): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.bon_earning.edit', [
            'bonEarning' => $bonEarning->load('conditions'),
        ]);
    }

    /**
     * Update A Bon Earning.
     */
    public function update(UpdateBonEarningRequest $request, BonEarning $bonEarning): \Illuminate\Http\RedirectResponse
    {
        $bonEarning->update($request->safe()->only(['name', 'description', 'position', 'variable', 'multiplier', 'operation']));

        $bonEarning->conditions()
            ->whereNotIn('id', Arr::flatten($request->safe()->only(['options.*.id'])))
            ->delete();
        BonEarningCondition::upsert(array_map(fn ($item) => ['bon_earning_id' => $bonEarning->id] + $item, $request->safe()->only(['conditions'])['conditions'] ?? []), ['id']);

        return to_route('staff.bon_earnings.index')
            ->withSuccess('Bon Exchange Successfully Modified');
    }

    /**
     * Destroy A Bon Earning.
     *
     * @throws Exception
     */
    public function destroy(BonEarning $bonEarning): \Illuminate\Http\RedirectResponse
    {
        $bonEarning->conditions()->delete();
        $bonEarning->delete();

        return to_route('staff.bon_earnings.index')
            ->withSuccess('Bon Exchange Successfully Deleted');
    }
}

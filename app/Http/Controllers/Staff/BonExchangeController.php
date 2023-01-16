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
use App\Http\Requests\Staff\StoreBonExchangeRequest;
use App\Http\Requests\Staff\UpdateBonExchangeRequest;
use App\Models\BonExchange;

class BonExchangeController extends Controller
{
    /**
     * Display All Bon Exchanges.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $bonExchanges = BonExchange::all()->sortBy('position');

        return \view('Staff.bon_exchange.index', ['bonExchanges' => $bonExchanges]);
    }

    /**
     * Show Form For Creating A New Bon Exchange.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('Staff.bon_exchange.create');
    }

    /**
     * Store A Bon Exchange.
     */
    public function store(StoreBonExchangeRequest $request): \Illuminate\Http\RedirectResponse
    {
        BonExchange::create([
            'upload'             => $request->type === 'upload',
            'download'           => $request->type === 'download',
            'personal_freeleech' => $request->type === 'personal_freeleech',
            'upload'             => $request->type === 'invite',
        ]
        + $request->validated());

        return \to_route('staff.bon_exchanges.index')
            ->withSuccess('Bon Exchange Successfully Added');
    }

    /**
     * Bon Exchange Edit Form.
     */
    public function edit(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $bonExchange = BonExchange::findOrFail($id);

        return \view('Staff.bon_exchange.edit', ['bonExchange' => $bonExchange]);
    }

    /**
     * Update A Bon Exchange.
     */
    public function update(UpdateBonExchangeRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        BonExchange::where('id', '=', $id)->update([
            'upload'             => $request->type === 'upload',
            'download'           => $request->type === 'download',
            'personal_freeleech' => $request->type === 'personal_freeleech',
            'upload'             => $request->type === 'invite',
        ]
        + $request->validated());

        return \to_route('staff.bon_exchanges.index')
            ->withSuccess('Bon Exchange Successfully Modified');
    }

    /**
     * Destroy A Bon Exchange.
     *
     * @throws \Exception
     */
    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        $bonExchange = BonExchange::findOrFail($id);
        $bonExchange->delete();

        return \to_route('staff.bon_exchanges.index')
            ->withSuccess('Bon Exchange Successfully Deleted');
    }
}

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
use App\Models\BonExchange;
use Illuminate\Http\Request;

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
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $bonExchange = new BonExchange();
        $bonExchange->description = $request->description;
        $bonExchange->value = $request->value;
        $bonExchange->cost = $request->cost;
        $bonExchange->upload = $request->type === 'upload';
        $bonExchange->download = $request->type === 'download';
        $bonExchange->personal_freeleech = $request->type === 'personal_freeleech';
        $bonExchange->invite = $request->type === 'invite';

        $v = \validator($bonExchange->toArray(), [
            'description'        => 'required',
            'value'              => 'required|numeric',
            'cost'               => 'required|numeric',
            'upload'             => 'required|boolean',
            'download'           => 'required|boolean',
            'personal_freeleech' => 'required|boolean',
            'invite'             => 'required|boolean',
        ]);

        if ($v->fails()) {
            return \to_route('staff.bon_exchanges.create')
                ->withErrors($v->errors());
        }

        $bonExchange->save();

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
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $bonExchange = BonExchange::findOrFail($id);
        $bonExchange->description = $request->description;
        $bonExchange->value = $request->value;
        $bonExchange->cost = $request->cost;
        $bonExchange->upload = $request->type === 'upload';
        $bonExchange->download = $request->type === 'download';
        $bonExchange->personal_freeleech = $request->type === 'personal_freeleech';
        $bonExchange->invite = $request->type === 'invite';

        $v = \validator($bonExchange->toArray(), [
            'description'        => 'required',
            'value'              => 'required|numeric',
            'cost'               => 'required|numeric',
            'upload'             => 'required|boolean',
            'download'           => 'required|boolean',
            'personal_freeleech' => 'required|boolean',
            'invite'             => 'required|boolean',
        ]);

        if ($v->fails()) {
            return \to_route('staff.bon_exchanges.edit', ['bonExchange' => $id])
                ->withErrors($v->errors());
        }

        $bonExchange->save();

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

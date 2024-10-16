<?php

declare(strict_types=1);

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

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('event.index', [
            'events' => Event::query()->where('active', '=', true)->orderBy('starts_at')->get(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Event $event): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('event.show', [
            'event'      => $event,
            'userPrizes' => $event
                ->claimedPrizes()
                ->where('user_id', '=', $request->user()->id)
                ->get()
                ->groupBy(fn ($claimedPrize) => $claimedPrize->created_at->diffInDays($event->starts_at)),
        ]);
    }
}

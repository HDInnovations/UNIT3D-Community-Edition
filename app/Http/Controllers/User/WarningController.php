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
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warning;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Carbon;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\WarningControllerTest
 */
class WarningController extends Controller
{
    /**
     * Manually warn a user.
     */
    protected function store(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->group->is_modo, 403);

        Warning::create([
            'user_id'    => $user->id,
            'warned_by'  => $request->user()->id,
            'torrent'    => null,
            'reason'     => $request->string('message'),
            'expires_on' => Carbon::now()->addDays(config('hitrun.expire')),
            'active'     => '1',
        ]);

        $user->sendSystemNotification(
            subject: 'Received warning',
            message: 'You have received a [b]warning[/b]. Reason: '.$request->string('message'),
        );

        return to_route('users.show', ['user' => $user])
            ->withSuccess('Warning issued successfully!');
    }

    /**
     * Delete A Warning.
     *
     *
     * @throws Exception
     */
    public function destroy(Request $request, User $user, Warning $warning): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->group->is_modo, 403);

        $staff = $request->user();

        $user->sendSystemNotification(
            subject: 'Hit and Run Warning Deleted',
            message: $staff->username.' has decided to delete your warning for torrent '.$warning->torrent.' You lucked out!',
        );

        $warning->update([
            'deleted_by' => $staff->id,
        ]);

        $warning->delete();

        return to_route('users.show', ['user' => $user])
            ->withSuccess('Warning Was Successfully Deleted');
    }

    /**
     * Delete All Warnings.
     */
    public function massDestroy(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->group->is_modo, 403);

        $staff = $request->user();

        $user->warnings()->update([
            'deleted_by' => $staff->id,
        ]);

        $user->warnings()->delete();

        $user->sendSystemNotification(
            subject: 'All Hit and Run Warnings Deleted',
            message: $staff->username.' has decided to delete all of your warnings. You lucked out!',
        );

        return to_route('users.show', ['user' => $user])
            ->withSuccess('All Warnings Were Successfully Deleted');
    }

    /**
     * Restore A Soft Deleted Warning.
     */
    public function update(Request $request, User $user, Warning $warning): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->group->is_modo, 403);

        $warning->restore();

        return to_route('users.show', ['user' => $user])
            ->withSuccess('Warning Was Successfully Restored');
    }
}

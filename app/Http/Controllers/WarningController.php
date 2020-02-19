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

namespace App\Http\Controllers;

use App\Models\PrivateMessage;
use App\Models\User;
use App\Models\Warning;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WarningController extends Controller
{
    /**
     * Show A Users Warnings.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $username
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $username)
    {
        abort_unless($request->user()->group->is_modo, 403);

        $user = User::where('username', '=', $username)->firstOrFail();

        $warnings = Warning::where('user_id', '=', $user->id)->with(['torrenttitle', 'warneduser'])->latest('active')->paginate(25);
        $warningcount = Warning::where('user_id', '=', $user->id)->count();

        $softDeletedWarnings = Warning::where('user_id', '=', $user->id)->with(['torrenttitle', 'warneduser'])->latest('created_at')->onlyTrashed()->paginate(25);
        $softDeletedWarningCount = Warning::where('user_id', '=', $user->id)->onlyTrashed()->count();

        return view('user.warninglog', [
            'warnings'                => $warnings,
            'warningcount'            => $warningcount,
            'softDeletedWarnings'     => $softDeletedWarnings,
            'softDeletedWarningCount' => $softDeletedWarningCount,
            'user'                    => $user,
        ]);
    }

    /**
     * Deactivate A Warning.
     *
     * @param Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function deactivate(Request $request, $id)
    {
        abort_unless($request->user()->group->is_modo, 403);
        $staff = $request->user();
        $warning = Warning::findOrFail($id);
        $warning->expires_on = Carbon::now();
        $warning->active = 0;
        $warning->save();

        // Send Private Message
        $pm = new PrivateMessage();
        $pm->sender_id = $staff->id;
        $pm->receiver_id = $warning->user_id;
        $pm->subject = 'Hit and Run Warning Deactivated';
        $pm->message = $staff->username.' has decided to deactivate your active warning for torrent '.$warning->torrent.' You lucked out! [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]';
        $pm->save();

        return redirect()->route('warnings.show', ['username' => $warning->warneduser->username])
            ->withSuccess('Warning Was Successfully Deactivated');
    }

    /**
     * Deactivate All Warnings.
     *
     * @param Request $request
     * @param $username
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function deactivateAllWarnings(Request $request, $username)
    {
        abort_unless($request->user()->group->is_modo, 403);
        $staff = $request->user();
        $user = User::where('username', '=', $username)->firstOrFail();

        $warnings = Warning::where('user_id', '=', $user->id)->get();

        foreach ($warnings as $warning) {
            $warning->expires_on = Carbon::now();
            $warning->active = 0;
            $warning->save();
        }

        // Send Private Message
        $pm = new PrivateMessage();
        $pm->sender_id = $staff->id;
        $pm->receiver_id = $user->id;
        $pm->subject = 'All Hit and Run Warning Deactivated';
        $pm->message = $staff->username.' has decided to deactivate all of your active hit and run warnings. You lucked out! [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]';
        $pm->save();

        return redirect()->route('warnings.show', ['username' => $user->username])
            ->withSuccess('All Warnings Were Successfully Deactivated');
    }

    /**
     * Delete A Warning.
     *
     * @param Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function deleteWarning(Request $request, $id)
    {
        abort_unless($request->user()->group->is_modo, 403);

        $staff = $request->user();
        $warning = Warning::findOrFail($id);

        // Send Private Message
        $pm = new PrivateMessage();
        $pm->sender_id = $staff->id;
        $pm->receiver_id = $warning->user_id;
        $pm->subject = 'Hit and Run Warning Deleted';
        $pm->message = $staff->username.' has decided to delete your warning for torrent '.$warning->torrent.' You lucked out! [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]';
        $pm->save();

        $warning->deleted_by = $staff->id;
        $warning->save();
        $warning->delete();

        return redirect()->route('warnings.show', ['username' => $warning->warneduser->username])
            ->withSuccess('Warning Was Successfully Deleted');
    }

    /**
     * Delete All Warnings.
     *
     * @param Request $request
     * @param $username
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function deleteAllWarnings(Request $request, $username)
    {
        abort_unless($request->user()->group->is_modo, 403);

        $staff = $request->user();
        $user = User::where('username', '=', $username)->firstOrFail();

        $warnings = Warning::where('user_id', '=', $user->id)->get();

        foreach ($warnings as $warning) {
            $warning->deleted_by = $staff->id;
            $warning->save();
            $warning->delete();
        }

        // Send Private Message
        $pm = new PrivateMessage();
        $pm->sender_id = $staff->id;
        $pm->receiver_id = $user->id;
        $pm->subject = 'All Hit and Run Warnings Deleted';
        $pm->message = $staff->username.' has decided to delete all of your warnings. You lucked out! [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]';
        $pm->save();

        return redirect()->route('warnings.show', ['username' => $user->username])
            ->withSuccess('All Warnings Were Successfully Deleted');
    }

    /**
     * Restore A Soft Deleted Warning.
     *
     * @param Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function restoreWarning(Request $request, $id)
    {
        abort_unless($request->user()->group->is_modo, 403);

        $staff = $request->user();
        $warning = Warning::withTrashed()->findOrFail($id);
        $warning->restore();

        return redirect()->route('warnings.show', ['username' => $warning->warneduser->username])
            ->withSuccess('Warning Was Successfully Restored');
    }
}

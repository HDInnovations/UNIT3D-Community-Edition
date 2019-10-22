<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\Models\PrivateMessage;
use App\Models\User;
use App\Models\Warning;

class WarningController extends Controller
{
    /**
     * Show A Users Warnings.
     *
     * @param  \App\Http\Controllers\Request  $request
     * @param                                 $username
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $username)
    {
        abort_unless($request->user()->group->is_modo, 403);

        $user = User::where('username', '=', $username)->firstOrFail();

        $warnings = Warning::where('user_id', '=', $user->id)->with(['torrenttitle', 'warneduser'])->latest('active')->paginate(25);
        $warningcount = Warning::where('user_id', '=', $id)->count();

        $softDeletedWarnings = Warning::where('user_id', '=', $user->id)->with(['torrenttitle', 'warneduser'])->latest('created_at')->onlyTrashed()->paginate(25);
        $softDeletedWarningCount = Warning::where('user_id', '=', $id)->onlyTrashed()->count();

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

        // Activity Log
        \LogActivity::addToLog("Staff Member {$staff->username} has deactivated a warning on {$warning->warneduser->username} account.");

        return redirect()->route('warninglog', ['username' => $warning->warneduser->username, 'id' => $warning->warneduser->id])
            ->withSuccess('Warning Was Successfully Deactivated');
    }

    /**
     * Deactivate All Warnings.
     *
     * @param Request $request
     * @param $username
     * @param $id
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
        $pm->receiver_id = $warning->user_id;
        $pm->subject = 'All Hit and Run Warning Deactivated';
        $pm->message = $staff->username.' has decided to deactivate all of your active hit and run warnings. You lucked out! [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]';
        $pm->save();

        // Activity Log
        \LogActivity::addToLog("Staff Member {$staff->username} has deactivated all warnings on {$warning->warneduser->username} account.");

        return redirect()->route('warninglog', ['username' => $warning->warneduser->username, 'id' => $warning->warneduser->id])
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

        // Activity Log
        \LogActivity::addToLog("Staff Member {$staff->username} has deleted a warning on {$warning->warneduser->username} account.");

        return redirect()->route('warninglog', ['username' => $warning->warneduser->username, 'id' => $warning->warneduser->id])
            ->withSuccess('Warning Was Successfully Deleted');
    }

    /**
     * Delete All Warnings.
     *
     * @param Request $request
     * @param $username
     * @param $id
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
        $pm->receiver_id = $warning->user_id;
        $pm->subject = 'All Hit and Run Warnings Deleted';
        $pm->message = $staff->username.' has decided to delete all of your warnings. You lucked out! [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]';
        $pm->save();

        // Activity Log
        \LogActivity::addToLog("Staff Member {$staff->username} has deleted all warnings on {$warning->warneduser->username} account.");

        return redirect()->route('warninglog', ['username' => $warning->warneduser->username, 'id' => $warning->warneduser->id])
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
        $warning = Warning::findOrFail($id);
        $warning->restore();

        // Activity Log
        \LogActivity::addToLog("Staff Member {$staff->username} has restore a soft deleted warning on {$warning->warneduser->username} account.");

        return redirect()->route('warninglog', ['username' => $warning->warneduser->username, 'id' => $warning->warneduser->id])
            ->withSuccess('Warning Was Successfully Restored');
    }
}

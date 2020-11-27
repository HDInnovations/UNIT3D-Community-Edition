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

use App\Models\Warning;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\WarningControllerTest
 */
class WarningController extends \App\Http\Controllers\Controller
{
    /**
     * Show A Users Warnings.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $username
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(\Illuminate\Http\Request $request, $username)
    {
        \abort_unless($request->user()->group->is_modo, 403);
        $user = \App\Models\User::where('username', '=', $username)->firstOrFail();
        $warnings = \App\Models\Warning::where('user_id', '=', $user->id)->with(['torrenttitle', 'warneduser'])->latest('active')->paginate(25);
        $warningcount = \App\Models\Warning::where('user_id', '=', $user->id)->count();
        $softDeletedWarnings = \App\Models\Warning::where('user_id', '=', $user->id)->with(['torrenttitle', 'warneduser'])->latest('created_at')->onlyTrashed()->paginate(25);
        $softDeletedWarningCount = \App\Models\Warning::where('user_id', '=', $user->id)->onlyTrashed()->count();

        return \view('user.warninglog', ['warnings' => $warnings, 'warningcount' => $warningcount, 'softDeletedWarnings' => $softDeletedWarnings, 'softDeletedWarningCount' => $softDeletedWarningCount, 'user' => $user]);
    }

    /**
     * Deactivate A Warning.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Warning      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deactivate(\Illuminate\Http\Request $request, $id)
    {
        \abort_unless($request->user()->group->is_modo, 403);
        $staff = $request->user();
        $warning = \App\Models\Warning::findOrFail($id);
        $warning->expires_on = \Carbon\Carbon::now();
        $warning->active = 0;
        $warning->save();
        // Send Private Message
        $privateMessage = new \App\Models\PrivateMessage();
        $privateMessage->sender_id = $staff->id;
        $privateMessage->receiver_id = $warning->user_id;
        $privateMessage->subject = 'Hit and Run Warning Deactivated';
        $privateMessage->message = $staff->username.' has decided to deactivate your active warning for torrent '.$warning->torrent.' You lucked out! [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]';
        $privateMessage->save();

        return \redirect()->route('warnings.show', ['username' => $warning->warneduser->username])->withSuccess('Warning Was Successfully Deactivated');
    }

    /**
     * Deactivate All Warnings.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $username
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deactivateAllWarnings(\Illuminate\Http\Request $request, $username)
    {
        \abort_unless($request->user()->group->is_modo, 403);
        $staff = $request->user();
        $user = \App\Models\User::where('username', '=', $username)->firstOrFail();
        $warnings = \App\Models\Warning::where('user_id', '=', $user->id)->get();
        foreach ($warnings as $warning) {
            $warning->expires_on = \Carbon\Carbon::now();
            $warning->active = 0;
            $warning->save();
        }
        // Send Private Message
        $privateMessage = new \App\Models\PrivateMessage();
        $privateMessage->sender_id = $staff->id;
        $privateMessage->receiver_id = $user->id;
        $privateMessage->subject = 'All Hit and Run Warning Deactivated';
        $privateMessage->message = $staff->username.' has decided to deactivate all of your active hit and run warnings. You lucked out! [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]';
        $privateMessage->save();

        return \redirect()->route('warnings.show', ['username' => $user->username])->withSuccess('All Warnings Were Successfully Deactivated');
    }

    /**
     * Delete A Warning.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Warning      $id
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteWarning(\Illuminate\Http\Request $request, $id)
    {
        \abort_unless($request->user()->group->is_modo, 403);
        $staff = $request->user();
        $warning = \App\Models\Warning::findOrFail($id);
        // Send Private Message
        $privateMessage = new \App\Models\PrivateMessage();
        $privateMessage->sender_id = $staff->id;
        $privateMessage->receiver_id = $warning->user_id;
        $privateMessage->subject = 'Hit and Run Warning Deleted';
        $privateMessage->message = $staff->username.' has decided to delete your warning for torrent '.$warning->torrent.' You lucked out! [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]';
        $privateMessage->save();
        $warning->deleted_by = $staff->id;
        $warning->save();
        $warning->delete();

        return \redirect()->route('warnings.show', ['username' => $warning->warneduser->username])->withSuccess('Warning Was Successfully Deleted');
    }

    /**
     * Delete All Warnings.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $username
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAllWarnings(\Illuminate\Http\Request $request, $username)
    {
        \abort_unless($request->user()->group->is_modo, 403);
        $staff = $request->user();
        $user = \App\Models\User::where('username', '=', $username)->firstOrFail();
        $warnings = \App\Models\Warning::where('user_id', '=', $user->id)->get();
        foreach ($warnings as $warning) {
            $warning->deleted_by = $staff->id;
            $warning->save();
            $warning->delete();
        }
        // Send Private Message
        $privateMessage = new \App\Models\PrivateMessage();
        $privateMessage->sender_id = $staff->id;
        $privateMessage->receiver_id = $user->id;
        $privateMessage->subject = 'All Hit and Run Warnings Deleted';
        $privateMessage->message = $staff->username.' has decided to delete all of your warnings. You lucked out! [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]';
        $privateMessage->save();

        return \redirect()->route('warnings.show', ['username' => $user->username])->withSuccess('All Warnings Were Successfully Deleted');
    }

    /**
     * Restore A Soft Deleted Warning.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Warning      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restoreWarning(\Illuminate\Http\Request $request, $id)
    {
        \abort_unless($request->user()->group->is_modo, 403);
        $staff = $request->user();
        $warning = \App\Models\Warning::withTrashed()->findOrFail($id);
        $warning->restore();

        return \redirect()->route('warnings.show', ['username' => $warning->warneduser->username])->withSuccess('Warning Was Successfully Restored');
    }
}

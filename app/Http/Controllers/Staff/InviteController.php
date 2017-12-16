<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Invite;

class InviteController extends Controller
{
    /**
     * Invites Log
     *
     *
     */
    public function getInvites()
    {
        $invites = Invite::orderBy('created_at', 'DESC')->paginate(50);
        $invitecount = Invite::count();

        return view('Staff.invites.index', ['invites' => $invites, 'invitecount' => $invitecount]);
    }
}

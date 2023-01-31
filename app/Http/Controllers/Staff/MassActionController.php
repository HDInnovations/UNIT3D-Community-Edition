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
use App\Http\Requests\Staff\StoreMassActionRequest;
use App\Jobs\ProcessMassPM;
use App\Models\Group;
use App\Models\User;

/**
 * @see \Tests\Feature\Http\Controllers\Staff\MassActionControllerTest
 */
class MassActionController extends Controller
{
    /**
     * @var int
     */
    private const SENDER_ID = 1;

    /**
     * Mass PM Form.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('Staff.masspm.index');
    }

    /**
     * Send The Mass PM.
     *
     * @throws \Exception
     */
    public function store(StoreMassActionRequest $request): \Illuminate\Http\RedirectResponse
    {
        $bannedGroup = \cache()->rememberForever('banned_group', fn () => Group::where('slug', '=', 'banned')->pluck('id'));
        $validatingGroup = \cache()->rememberForever('validating_group', fn () => Group::where('slug', '=', 'validating')->pluck('id'));
        $disabledGroup = \cache()->rememberForever('disabled_group', fn () => Group::where('slug', '=', 'disabled')->pluck('id'));
        $prunedGroup = \cache()->rememberForever('pruned_group', fn () => Group::where('slug', '=', 'pruned')->pluck('id'));
        $users = User::whereIntegerNotInRaw('group_id', [$validatingGroup[0], $bannedGroup[0], $disabledGroup[0], $prunedGroup[0]])->pluck('id');

        foreach ($users as $userId) {
            ProcessMassPM::dispatch(self::SENDER_ID, $userId, $request->subject, $request->message);
        }

        return \to_route('staff.mass-pm.create')
            ->withSuccess('MassPM Sent');
    }

    /**
     * Mass Validate Unvalidated Users.
     *
     * @throws \Exception
     */
    public function update(): \Illuminate\Http\RedirectResponse
    {
        $validatingGroup = \cache()->rememberForever('validating_group', fn () => Group::where('slug', '=', 'validating')->pluck('id'));
        $memberGroup = \cache()->rememberForever('member_group', fn () => Group::where('slug', '=', 'user')->pluck('id'));
        foreach (User::where('group_id', '=', $validatingGroup[0])->get() as $user) {
            $user->group_id = $memberGroup[0];
            $user->active = 1;
            $user->can_upload = 1;
            $user->can_download = 1;
            $user->can_request = 1;
            $user->can_comment = 1;
            $user->can_invite = 1;
            $user->save();
        }

        return \to_route('staff.dashboard.index')
            ->withSuccess('Unvalidated Accounts Are Now Validated');
    }
}

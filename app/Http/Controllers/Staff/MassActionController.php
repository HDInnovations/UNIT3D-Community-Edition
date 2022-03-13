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
use App\Jobs\ProcessMassPM;
use App\Models\User;
use Illuminate\Http\Request;

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
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $bannedRole = \cache()->rememberForever('banned_role', fn () => Role::where('slug', '=', 'banned')->pluck('id'));
        $validatingRole = \cache()->rememberForever('validating_role', fn () => Role::where('slug', '=', 'validating')->pluck('id'));
        $disabledRole = \cache()->rememberForever('disabled_role', fn () => Role::where('slug', '=', 'disabled')->pluck('id'));
        $prunedRole = \cache()->rememberForever('pruned_role', fn () => Role::where('slug', '=', 'pruned')->pluck('id'));
        $users = User::whereNotIn('role_id', [$validatingRole[0], $bannedRole[0], $disabledRole[0], $prunedRole[0]])->pluck('id');

        $subject = $request->input('subject');
        $message = $request->input('message');

        $v = \validator($request->all(), [
            'subject' => 'required|min:5',
            'message' => 'required|min:5',
        ]);

        if ($v->fails()) {
            return \to_route('staff.mass-pm.create')
                ->withErrors($v->errors());
        }

        foreach ($users as $userId) {
            ProcessMassPM::dispatch(self::SENDER_ID, $userId, $subject, $message);
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
        $validatingRole = \cache()->rememberForever('validating_role', fn () => Role::where('slug', '=', 'validating')->pluck('id'));
        $memberRole = \cache()->rememberForever('member_role', fn () => Role::where('slug', '=', 'user')->pluck('id'));
        foreach (User::where('role_id', '=', $validatingRole[0])->get() as $user) {
            $user->role_id = $memberRole[0];
            $user->active = 1;
            $user->save();
        }

        return \to_route('staff.dashboard.index')
            ->withSuccess('Unvalidated Accounts Are Now Validated');
    }
}

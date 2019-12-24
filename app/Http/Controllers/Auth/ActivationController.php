<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\UserActivation;
use Illuminate\Routing\Redirector;
use Illuminate\Translation\Translator;

final class ActivationController extends Controller
{
    /**
     * @var \Illuminate\Routing\Redirector
     */
    private $redirector;
    /**
     * @var \Illuminate\Translation\Translator
     */
    private $translator;

    public function __construct(Redirector $redirector, Translator $translator)
    {
        $this->redirector = $redirector;
        $this->translator = $translator;
    }

    /**
     * @param $token
     *
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function activate($token)
    {
        $banned_group = cache()->rememberForever('banned_group', fn () => Group::where('slug', '=', 'banned')->pluck('id'));
        $member_group = cache()->rememberForever('member_group', fn () => Group::where('slug', '=', 'user')->pluck('id'));

        $activation = UserActivation::with('user')->where('token', '=', $token)->firstOrFail();
        if ($activation->user->id && $activation->user->group->id != $banned_group[0]) {
            $activation->user->active = 1;
            $activation->user->can_upload = 1;
            $activation->user->can_download = 1;
            $activation->user->can_request = 1;
            $activation->user->can_comment = 1;
            $activation->user->can_invite = 1;
            $activation->user->group_id = $member_group[0];
            $activation->user->save();

            $activation->delete();

            return $this->redirector->route('login')
                ->withSuccess($this->translator->trans('auth.activation-success'));
        } else {
            return $this->redirector->route('login')
                ->withErrors($this->translator->trans('auth.activation-error'));
        }
    }
}

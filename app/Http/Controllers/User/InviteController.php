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

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\InviteUser;
use App\Models\Invite;
use App\Models\User;
use App\Rules\EmailBlacklist;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\InviteControllerTest
 */
class InviteController extends Controller
{
    /**
     * Invite Tree.
     */
    public function index(Request $request, string $username): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();
        $owner = User::where('username', '=', $username)->firstOrFail();
        \abort_unless($user->group->is_modo || $user->id === $owner->id, 403);

        $invites = Invite::with(['sender', 'receiver'])->where('user_id', '=', $owner->id)->latest()->paginate(25);

        return \view('user.invite.index', ['user' => $owner, 'invites' => $invites, 'route' => 'invite']);
    }

    /**
     * Invite Form.
     */
    public function create(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        if (! \config('other.invite-only')) {
            return \to_route('home.index')
            ->withErrors(\trans('user.invites-disabled'));
        }

        if ($user->can_invite == 0) {
            return \to_route('home.index')
            ->withErrors(\trans('user.invites-banned'));
        }

        if (\config('other.invites_restriced') && ! \in_array($user->group->name, \config('other.invite_groups'), true)) {
            return \to_route('home.index')
                ->withErrors(\trans('user.invites-disabled-group'));
        }

        return \view('user.invite.create', ['user' => $user, 'route' => 'invite']);
    }

    /**
     * Send Invite.
     *
     * @throws \Exception
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $carbon = new Carbon();
        $user = $request->user();

        if (\config('other.invites_restriced') && ! \in_array($user->group->name, \config('other.invite_groups'), true)) {
            return \to_route('home.index')
                ->withErrors(\trans('user.invites-disabled-group'));
        }

        if ($user->invites <= 0) {
            return \to_route('invites.create')
                ->withErrors(\trans('user.not-enough-invites'));
        }

        $exist = Invite::where('email', '=', $request->input('email'))->first();

        if ($exist) {
            return \to_route('invites.create')
                ->withErrors(\trans('user.invite-already-sent'));
        }

        $code = Uuid::uuid4()->toString();
        $invite = new Invite();
        $invite->user_id = $user->id;
        $invite->email = $request->input('email');
        $invite->code = $code;
        $invite->expires_on = $carbon->copy()->addDays(\config('other.invite_expire'));
        $invite->custom = $request->input('message');

        if (\config('email-blacklist.enabled')) {
            $v = \validator($invite->toArray(), [
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:70',
                    'unique:invites',
                    'unique:users',
                    'unique:applications',
                    new EmailBlacklist(),
                ],
                'custom' => 'required',
            ]);
        } else {
            $v = \validator($invite->toArray(), [
                'email'  => 'required|string|email|max:70|unique:users',
                'custom' => 'required',
            ]);
        }

        if ($v->fails()) {
            return \to_route('invites.create')
                ->withErrors($v->errors());
        }

        Mail::to($request->input('email'))->send(new InviteUser($invite));
        $invite->save();
        $user->invites--;
        $user->save();

        return \to_route('invites.create')
            ->withSuccess(\trans('user.invite-sent-success'));
    }

    /**
     * Resend Invite.
     */
    public function send(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $invite = Invite::findOrFail($id);

        \abort_unless($invite->user_id === $user->id, 403);

        if ($invite->accepted_by !== null) {
            return \to_route('invites.index', ['username' => $user->username])
                ->withErrors(\trans('user.invite-already-used'));
        }

        Mail::to($invite->email)->send(new InviteUser($invite));

        return \to_route('invites.index', ['username' => $user->username])
            ->withSuccess(\trans('user.invite-resent-success'));
    }
}

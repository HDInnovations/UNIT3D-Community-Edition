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

namespace App\Http\Controllers;

use App\Mail\InviteUser;
use App\Models\Invite;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;
use Illuminate\Routing\Redirector;
use Ramsey\Uuid\Uuid;

final class InviteController extends Controller
{
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private $viewFactory;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $configRepository;
    /**
     * @var \Illuminate\Routing\Redirector
     */
    private $redirector;
    /**
     * @var \Illuminate\Mail\Mailer
     */
    private $mailer;

    public function __construct(Factory $viewFactory, Repository $configRepository, Redirector $redirector, Mailer $mailer)
    {
        $this->viewFactory = $viewFactory;
        $this->configRepository = $configRepository;
        $this->redirector = $redirector;
        $this->mailer = $mailer;
    }

    /**
     * Invite Tree.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param $username
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, $username): Factory
    {
        $user = $request->user();
        $owner = User::where('username', '=', $username)->firstOrFail();
        abort_unless($user->group->is_modo || $user->id === $owner->id, 403);

        $invites = Invite::with(['sender', 'receiver'])->where('user_id', '=', $owner->id)->latest()->paginate(25);

        return $this->viewFactory->make('user.invites', ['owner' => $owner, 'invites' => $invites, 'route' => 'invite']);
    }

    /**
     * Invite Form.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($this->configRepository->get('other.invite-only') == false) {
            return $this->redirector->route('home.index')
            ->withErrors('Invitations Are Disabled Due To Open Registration!');
        }
        if ($user->can_invite == 0) {
            return $this->redirector->route('home.index')
            ->withErrors('Your Invite Rights Have Been Revoked!');
        }
        if ($this->configRepository->get('other.invites_restriced') == true && ! in_array($user->group->name, $this->configRepository->get('other.invite_groups'))) {
            return $this->redirector->route('home.index')
                ->withErrors('Invites are currently disabled for your group.');
        }

        return $this->viewFactory->make('user.invite', ['user' => $user, 'route' => 'invite']);
    }

    /**
     * Send Invite.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Exception
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function store(Request $request)
    {
        $current = new Carbon();
        $user = $request->user();

        if ($this->configRepository->get('other.invites_restriced') == true && ! in_array($user->group->name, $this->configRepository->get('other.invite_groups'))) {
            return $this->redirector->route('home.index')
                ->withErrors('Invites are currently disabled for your group.');
        }

        if ($user->invites <= 0) {
            return $this->redirector->route('invites.create')
                ->withErrors('You do not have enough invites!');
        }

        $exist = Invite::where('email', '=', $request->input('email'))->first();

        if ($exist) {
            return $this->redirector->route('invites.create')
                ->withErrors('The email address your trying to send a invite to has already been sent one.');
        }

        $code = Uuid::uuid4()->toString();
        $invite = new Invite();
        $invite->user_id = $user->id;
        $invite->email = $request->input('email');
        $invite->code = $code;
        $invite->expires_on = $current->copy()->addDays($this->configRepository->get('other.invite_expire'));
        $invite->custom = $request->input('message');

        if ($this->configRepository->get('email-white-blacklist.enabled') === 'allow') {
            $v = validator($invite->toArray(), [
            'email'  => 'required|email|unique:users|email_list:allow', // Whitelist
            'custom' => 'required',
            ]);
        } elseif ($this->configRepository->get('email-white-blacklist.enabled') === 'block') {
            $v = validator($invite->toArray(), [
            'email'  => 'required|email|unique:users|email_list:block', // Blacklist
            'custom' => 'required',
            ]);
        } else {
            $v = validator($invite->toArray(), [
            'email'  => 'required|email|unique:users', // Default
            'custom' => 'required',
            ]);
        }

        if ($v->fails()) {
            return $this->redirector->route('invites.create')
                ->withErrors($v->errors());
        } else {
            $this->mailer->to($request->input('email'))->send(new InviteUser($invite));
            $invite->save();

            $user->invites--;
            $user->save();

            return $this->redirector->route('invites.create')
                ->withSuccess('Invite was sent successfully!');
        }
    }

    /**
     * Resend Invite.
     *
     * @param \Illuminate\Http\Request  $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function send(Request $request, $id)
    {
        $user = $request->user();
        $invite = Invite::findOrFail($id);

        abort_unless($invite->user_id === $user->id, 403);

        if ($invite->accepted_by !== null) {
            return $this->redirector->route('invites.index', ['username' => $user->username])
                ->withErrors('The invite you are trying to resend has already been used.');
        }

        $this->mailer->to($invite->email)->send(new InviteUser($invite));

        return $this->redirector->route('invites.index', ['username' => $user->username])
            ->withSuccess('Invite was resent successfully!');
    }
}

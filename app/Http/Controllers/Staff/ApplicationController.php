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
use App\Mail\DenyApplication;
use App\Mail\InviteUser;
use App\Models\Application;
use App\Models\Invite;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;

class ApplicationController extends Controller
{
    /**
     * Display All Applications.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $applications = Application::withAnyStatus()
            ->with(['user', 'moderated', 'imageProofs', 'urlProofs'])
            ->latest()
            ->paginate(25);

        return view('Staff.application.index', ['applications' => $applications]);
    }

    /**
     * Get A Application.
     *
     * @param  $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $application = Application::withAnyStatus()->with(['user', 'moderated', 'imageProofs', 'urlProofs'])->findOrFail($id);

        return view('Staff.application.show', ['application' => $application]);
    }

    /**
     * Approve A Application.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, $id)
    {
        $application = Application::withAnyStatus()->findOrFail($id);

        if ($application->status !== 1) {
            $current = new Carbon();
            $user = $request->user();

            $code = Uuid::uuid4()->toString();
            $invite = new Invite();
            $invite->user_id = $user->id;
            $invite->email = $application->email;
            $invite->code = $code;
            $invite->expires_on = $current->copy()->addDays(config('other.invite_expire'));
            $invite->custom = $request->input('approve');

            if (config('email-white-blacklist.enabled') === 'allow') {
                $v = validator($request->all(), [
                    'email'   => 'required|email|unique:invites|unique:users|email_list:allow', // Whitelist
                    'approve' => 'required',
                ]);
            } elseif (config('email-white-blacklist.enabled') === 'block') {
                $v = validator($request->all(), [
                    'email'   => 'required|email|unique:invites|unique:users|email_list:block', // Blacklist
                    'approve' => 'required',
                ]);
            } else {
                $v = validator($request->all(), [
                    'email'   => 'required|email|unique:invites|unique:users', // Default
                    'approve' => 'required',
                ]);
            }

            if ($v->fails()) {
                return redirect()->route('staff.applications.index')
                    ->withErrors($v->errors());
            }
            Mail::to($application->email)->send(new InviteUser($invite));
            $invite->save();
            $application->markApproved();

            return redirect()->route('staff.applications.index')
                ->withSuccess('Application Approved');
        } else {
            return redirect()->route('staff.applications.index')
                ->withErrors('Application Already Approved');
        }
    }

    /**
     * Reject A Application.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, $id)
    {
        $application = Application::withAnyStatus()->findOrFail($id);

        if ($application->status !== 2) {
            $denied_message = $request->input('deny');
            $v = validator($request->all(), [
                'deny' => 'required',
            ]);

            $application->markRejected();
            Mail::to($application->email)->send(new DenyApplication($denied_message));

            return redirect()->route('staff.applications.index')
                ->withSuccess('Application Rejected');
        }

        return redirect()->route('staff.applications.index')
            ->withErrors('Application Already Rejected');
    }
}

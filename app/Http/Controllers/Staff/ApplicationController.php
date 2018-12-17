<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Application;
use Brian2694\Toastr\Toastr;

class ApplicationController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * ApplicationController Constructor
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Get All Applications
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $applications = Application::withAnyStatus()
            ->with(['user', 'moderated', 'imageProofs', 'urlProofs'])
            ->withCount(['imageProofs', 'urlProofs'])
            ->latest()
            ->paginate(25);

        return view('Staff.application.index', ['applications' => $applications]);
    }

    /**
     * Get A Application
     *
     * @param  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $application = Application::withAnyStatus()->with(['user', 'moderated', 'imageProofs', 'urlProofs'])->findOrFail($id);

        return view('Staff.application.application', ['application' => $application]);
    }

    /**
     * Approve A Application
     *
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function approve($id)
    {
        $application = Application::withAnyStatus()->findOrFail($id);

        if ($application->status !== 1) {
            $application->markApproved();

            $current = new Carbon();
            $user = auth()->user();

            $code = Uuid::uuid4()->toString();
            $invite = new Invite();
            $invite->user_id = $user->id;
            $invite->email = $application->email;
            $invite->code = $code;
            $invite->expires_on = $current->copy()->addDays(config('other.invite_expire'));
            $invite->custom = "Your Application Has Been Approved!";

            if (config('email-white-blacklist.enabled') === 'allow') {
                $v = validator($invite->toArray(), [
                    "email" => "required|email|unique:invites|unique:users|email_list:allow", // Whitelist
                    "custom" => "required"
                ]);
            } elseif (config('email-white-blacklist.enabled') === 'block') {
                $v = validator($invite->toArray(), [
                    "email" => "required|email|unique:invites|unique:users|email_list:block", // Blacklist
                    "custom" => "required"
                ]);
            } else {
                $v = validator($invite->toArray(), [
                    "email" => "required|email|unique:invites|unique:users", // Default
                    "custom" => "required"
                ]);
            }


            if ($v->fails()) {
                return redirect()->route('invite')
                    ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
            } else {
                Mail::to($application->email)->send(new InviteUser($invite));
                $invite->save();

                // Activity Log
                \LogActivity::addToLog("Staff member {$user->username} has approved {$application->email} application.");

                return redirect()->route('staff_applications')
                    ->with($this->toastr->success('Application Approved', 'Yay!', ['options']));
            }

        } else {
            return redirect()->back()
                ->with($this->toastr->error('Application Already Approved', 'Whoops!', ['options']));
        }
    }

    /**
     * Reject A Application
     *
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function reject($id)
    {
        $application = Application::withAnyStatus()->findOrFail($id);

        if ($application->status !== 2) {
            $application->markRejected();
            return redirect()->route('staff_applications')
                ->with($this->toastr->info('Application Rejected', 'Info!', ['options']));
        } else {
            return redirect()->back()
                ->with($this->toastr->error('Application Already Rejected', 'Whoops!', ['options']));
        }
    }
}

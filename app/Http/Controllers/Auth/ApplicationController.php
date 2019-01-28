<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Auth;

use App\Application;
use App\ApplicationUrlProof;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use App\ApplicationImageProof;
use App\Http\Controllers\Controller;

class ApplicationController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * ApplicationController Constructor.
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Application Add Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('auth.application.create');
    }

    /**
     * Add A Application.
     *
     * @param \Illuminate\Http\Request $request
     * @return Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $application = new Application();
        $application->type = $request->input('type');
        $application->email = $request->input('email');
        $application->referrer = $request->input('referrer');

        if (config('email-white-blacklist.enabled') === 'allow') {
            $v = validator($request->all(), [
                'type' => 'required',
                'email' => 'required|email|unique:invites|unique:users|unique:applications|email_list:allow',
                'referrer' => 'required',
                'images.*' => 'filled',
                'images'   => 'min:2',
                'links.*' => 'filled',
                'links'   => 'min:2',
            ]);
        } elseif (config('email-white-blacklist.enabled') === 'block') {
            $v = validator($request->all(), [
                'type' => 'required',
                'email' => 'required|email|unique:invites|unique:users|unique:applications|email_list:block',
                'referrer' => 'required',
                'images.*' => 'filled',
                'images'   => 'min:2',
                'links.*' => 'filled',
                'links'   => 'min:2',
            ]);
        } else {
            $v = validator($request->all(), [
                'type' => 'required',
                'email' => 'required|email|unique:invites|unique:users|unique:applications',
                'referrer' => 'required',
                'images.*' => 'filled',
                'images'   => 'min:2',
                'links.*' => 'filled',
                'links'   => 'min:2',
            ]);
        }

        if ($v->fails()) {
            return redirect()->route('application.create')
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $application->save();

            // Map And Save IMG Proofs
            $imgs = collect($request->input('images'))->map(function ($value) {
                return new ApplicationImageProof(['image' => $value]);
            });
            $application->imageProofs()->saveMany($imgs);

            // Map And Save URL Proofs
            $urls = collect($request->input('links'))->map(function ($value) {
                return new ApplicationUrlProof(['url' => $value]);
            });
            $application->urlProofs()->saveMany($urls);

            return redirect()->route('login')
                ->with($this->toastr->success('Your Application Has Been Submitted. You will receive a email soon!', 'Yay!', ['options']));
        }
    }
}

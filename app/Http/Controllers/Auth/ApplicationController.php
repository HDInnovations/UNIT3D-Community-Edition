<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Auth;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Models\ApplicationUrlProof;
use App\Http\Controllers\Controller;
use App\Models\ApplicationImageProof;

class ApplicationController extends Controller
{
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
     * Store A New Application.
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
            if (config('captcha.enabled') == false) {
                $v = validator($request->all(), [
                    'type' => 'required',
                    'email' => 'required|email|unique:invites|unique:users|unique:applications|email_list:allow',
                    'referrer' => 'required',
                    'images.*' => 'filled',
                    'images'   => 'min:2',
                    'links.*' => 'filled',
                    'links'   => 'min:2',
                ]);
            } else {
                $v = validator($request->all(), [
                    'type' => 'required',
                    'email' => 'required|email|unique:invites|unique:users|unique:applications|email_list:allow',
                    'referrer' => 'required',
                    'images.*' => 'filled',
                    'images'   => 'min:2',
                    'links.*' => 'filled',
                    'links'   => 'min:2',
                    'g-recaptcha-response' => 'required|recaptcha',
                ]);
            }
        } elseif (config('email-white-blacklist.enabled') === 'block') {
            if (config('captcha.enabled') == false) {
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
                    'email' => 'required|email|unique:invites|unique:users|unique:applications|email_list:block',
                    'referrer' => 'required',
                    'images.*' => 'filled',
                    'images'   => 'min:2',
                    'links.*' => 'filled',
                    'links'   => 'min:2',
                    'g-recaptcha-response' => 'required|recaptcha',
                ]);
            }
        } else {
            if (config('captcha.enabled') == false) {
                $v = validator($request->all(), [
                    'type' => 'required',
                    'email' => 'required|email|unique:invites|unique:users|unique:applications',
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
                    'g-recaptcha-response' => 'required|recaptcha',
                ]);
            }
        }

        if ($v->fails()) {
            return redirect()->route('application.create')
                ->withErrors($v->errors());
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
                ->withSuccess(trans('application-submitted'));
        }
    }
}

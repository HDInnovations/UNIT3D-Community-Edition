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
use App\Models\Application;
use App\Models\ApplicationImageProof;
use App\Models\ApplicationUrlProof;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ApplicationController extends Controller
{
    /**
     * Application Add Form.
     *
     * @return Factory|View
     */
    public function create()
    {
        return view('auth.application.create');
    }

    /**
     * Store A New Application.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
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
                    'type'     => 'required',
                    'email'    => 'required|email|unique:invites|unique:users|unique:applications|email_list:allow',
                    'referrer' => 'required',
                    'images.*' => 'filled',
                    'images'   => 'min:2',
                    'links.*'  => 'filled',
                    'links'    => 'min:2',
                ]);
            } else {
                $v = validator($request->all(), [
                    'type'     => 'required',
                    'email'    => 'required|email|unique:invites|unique:users|unique:applications|email_list:allow',
                    'referrer' => 'required',
                    'images.*' => 'filled',
                    'images'   => 'min:2',
                    'links.*'  => 'filled',
                    'links'    => 'min:2',
                    'captcha'  => 'hiddencaptcha',
                ]);
            }
        } elseif (config('email-white-blacklist.enabled') === 'block') {
            if (config('captcha.enabled') == false) {
                $v = validator($request->all(), [
                    'type'     => 'required',
                    'email'    => 'required|email|unique:invites|unique:users|unique:applications|email_list:block',
                    'referrer' => 'required',
                    'images.*' => 'filled',
                    'images'   => 'min:2',
                    'links.*'  => 'filled',
                    'links'    => 'min:2',
                ]);
            } else {
                $v = validator($request->all(), [
                    'type'     => 'required',
                    'email'    => 'required|email|unique:invites|unique:users|unique:applications|email_list:block',
                    'referrer' => 'required',
                    'images.*' => 'filled',
                    'images'   => 'min:2',
                    'links.*'  => 'filled',
                    'links'    => 'min:2',
                    'captcha'  => 'hiddencaptcha',
                ]);
            }
        } elseif (config('captcha.enabled') == false) {
            $v = validator($request->all(), [
                'type'     => 'required',
                'email'    => 'required|email|unique:invites|unique:users|unique:applications',
                'referrer' => 'required',
                'images.*' => 'filled',
                'images'   => 'min:2',
                'links.*'  => 'filled',
                'links'    => 'min:2',
            ]);
        } else {
            $v = validator($request->all(), [
                'type'     => 'required',
                'email'    => 'required|email|unique:invites|unique:users|unique:applications',
                'referrer' => 'required',
                'images.*' => 'filled',
                'images'   => 'min:2',
                'links.*'  => 'filled',
                'links'    => 'min:2',
                'captcha'  => 'hiddencaptcha',
            ]);
        }

        if ($v->fails()) {
            return redirect()->route('application.create')
                ->withErrors($v->errors());
        }
        $application->save();
        // Map And Save IMG Proofs
        $imgs = collect($request->input('images'))->map(fn ($value): \App\Models\ApplicationImageProof => new ApplicationImageProof(['image' => $value]));
        $application->imageProofs()->saveMany($imgs);
        // Map And Save URL Proofs
        $urls = collect($request->input('links'))->map(fn ($value): \App\Models\ApplicationUrlProof => new ApplicationUrlProof(['url' => $value]));
        $application->urlProofs()->saveMany($urls);

        return redirect()->route('login')
            ->withSuccess(trans('application-submitted'));
    }
}

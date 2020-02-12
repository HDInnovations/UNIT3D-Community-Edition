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

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\TwoStep;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TwoStepController extends Controller
{
    use TwoStep;

    private $_authCount;

    private $_authStatus;

    private $_twoStepAuth;

    private $_remainingAttempts;

    private $_user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->setUser2StepData();

            return $next($request);
        });
    }

    /**
     * Set the User2Step Variables.
     *
     * @return void
     */
    private function setUser2StepData()
    {
        $user = auth()->user();
        $twoStepAuth = $this->getTwoStepAuthStatus($user->id);
        $authCount = $twoStepAuth->authCount;
        $this->_user = $user;
        $this->_twoStepAuth = $twoStepAuth;
        $this->_authCount = $authCount;
        $this->_authStatus = $twoStepAuth->authStatus;
        $this->_remainingAttempts = config('auth.TwoStepExceededCount') - $authCount;
    }

    /**
     * Validation and Invalid code failed actions and return message.
     *
     * @param array $errors (optional)
     *
     * @return array
     */
    private function invalidCodeReturnData($errors = null)
    {
        $this->_authCount = $this->_twoStepAuth->authCount += 1;
        $this->_twoStepAuth->save();

        $returnData = [
            'message'           => trans('auth.titleFailed'),
            'authCount'         => $this->_authCount,
            'remainingAttempts' => $this->_remainingAttempts,
        ];

        if ($errors) {
            $returnData['errors'] = $errors;
        }

        return $returnData;
    }

    /**
     * Show the twostep verification form.
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\Response
     */
    public function showVerification()
    {
        if (!config('auth.TwoStepEnabled')) {
            abort(404);
        }

        $twoStepAuth = $this->_twoStepAuth;
        $authStatus = $this->_authStatus;

        if ($this->checkExceededTime($twoStepAuth->updated_at)) {
            $this->resetExceededTime($twoStepAuth);
        }

        $data = [
            'user'              => $this->_user,
            'remainingAttempts' => $this->_remainingAttempts + 1,
        ];

        if ($this->_authCount > config('auth.TwoStepExceededCount')) {
            $exceededTimeDetails = $this->exceededTimeParser($twoStepAuth->updated_at);

            $data['timeUntilUnlock'] = $exceededTimeDetails['tomorrow'];
            $data['timeCountdownUnlock'] = $exceededTimeDetails['remaining'];

            return view('auth.twostep-exceeded')->with($data);
        }

        $now = new Carbon();
        $sentTimestamp = $twoStepAuth->requestDate;

        if (!$twoStepAuth->authCode) {
            $twoStepAuth->authCode = $this->generateCode();
            $twoStepAuth->save();
        }

        if (!$sentTimestamp) {
            $this->sendVerificationCodeNotification($twoStepAuth);
        } else {
            $timeBuffer = config('laravel2step.laravel2stepTimeResetBufferSeconds');
            $timeAllowedToSendCode = $sentTimestamp->addSeconds($timeBuffer);
            if ($now->gt($timeAllowedToSendCode)) {
                $this->sendVerificationCodeNotification($twoStepAuth);
                $twoStepAuth->requestDate = new Carbon();
                $twoStepAuth->save();
            }
        }

        return view('auth.twostep-verification')->with($data);
    }

    /**
     * Verify the user code input.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request)
    {
        if (!config('auth.TwoStepEnabled')) {
            abort(404);
        }

        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'v_input_1' => 'required|min:1|max:1',
                'v_input_2' => 'required|min:1|max:1',
                'v_input_3' => 'required|min:1|max:1',
                'v_input_4' => 'required|min:1|max:1',
            ]);

            if ($validator->fails()) {
                $returnData = $this->invalidCodeReturnData($validator->errors());

                return response()->json($returnData, 418);
            }

            $code = $request->v_input_1.$request->v_input_2.$request->v_input_3.$request->v_input_4;
            $validCode = $this->_twoStepAuth->authCode;

            if ($validCode != $code) {
                $returnData = $this->invalidCodeReturnData();

                return response()->json($returnData, 418);
            }

            $this->resetActivationCountdown($this->_twoStepAuth);

            $returnData = [
                'nextUri' => session('nextUri', '/'),
                'message' => trans('auth.titlePassed'),
            ];

            return response()->json($returnData, 200);
        }
        abort(404);
    }

    /**
     * Resend the validation code triggered by user.
     *
     * @return \Illuminate\Http\Response
     */
    public function resend()
    {
        if (!config('auth.TwoStepEnabled')) {
            abort(404);
        }

        $twoStepAuth = $this->_twoStepAuth;
        $this->sendVerificationCodeNotification($twoStepAuth);

        $returnData = [
            'title'   => trans('auth.verificationEmailSuccess'),
            'message' => trans('auth.verificationEmailSentMsg'),
        ];

        return response()->json($returnData, 200);
    }
}

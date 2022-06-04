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
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * @see \Tests\Feature\Http\Controllers\Auth\TwoStepControllerTest
 */
class TwoStepController extends Controller
{
    use TwoStep;

    private $authCount;

    private $authStatus;

    private $twoStepAuth;

    private $remainingAttempts;

    private ?\Illuminate\Contracts\Auth\Authenticatable $user = null;

    /**
     * Create a new controller instance.
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
     */
    private function setUser2StepData(): void
    {
        $user = \auth()->user();
        $twoStepAuth = $this->getTwoStepAuthStatus($user->id);
        $authCount = $twoStepAuth->authCount;
        $this->user = $user;
        $this->twoStepAuth = $twoStepAuth;
        $this->authCount = $authCount;
        $this->authStatus = $twoStepAuth->authStatus;
        $this->remainingAttempts = \config('auth.TwoStepExceededCount') - $authCount;
    }

    /**
     * Validation and Invalid code failed actions and return message.
     */
    private function invalidCodeReturnData($errors = null): array
    {
        $this->authCount = ++$this->twoStepAuth->authCount;
        $this->twoStepAuth->save();

        $returnData = [
            'message'           => \trans('auth.titleFailed'),
            'authCount'         => $this->authCount,
            'remainingAttempts' => $this->remainingAttempts,
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
     */
    public function showVerification(): \Illuminate\Contracts\View\View
    {
        \abort_if(! \config('auth.TwoStepEnabled'), 404);

        $twoStepAuth = $this->twoStepAuth;
        $authStatus = $this->authStatus;

        if ($this->checkExceededTime($twoStepAuth->updated_at)) {
            $this->resetExceededTime($twoStepAuth);
        }

        $data = [
            'user'              => $this->user,
            'remainingAttempts' => $this->remainingAttempts + 1,
        ];

        if ($this->authCount > \config('auth.TwoStepExceededCount')) {
            $exceededTimeDetails = $this->exceededTimeParser($twoStepAuth->updated_at);

            $data['timeUntilUnlock'] = $exceededTimeDetails['tomorrow'];
            $data['timeCountdownUnlock'] = $exceededTimeDetails['remaining'];

            return \view('auth.twostep-exceeded')->with($data);
        }

        $carbon = new Carbon();
        $sentTimestamp = $twoStepAuth->requestDate;

        if (! $twoStepAuth->authCode) {
            $twoStepAuth->authCode = $this->generateCode();
            $twoStepAuth->save();
        }

        if (! $sentTimestamp) {
            $this->sendVerificationCodeNotification($twoStepAuth);
        } else {
            $timeBuffer = \config('laravel2step.laravel2stepTimeResetBufferSeconds');
            $timeAllowedToSendCode = $sentTimestamp->addSeconds($timeBuffer);
            if ($carbon->gt($timeAllowedToSendCode)) {
                $this->sendVerificationCodeNotification($twoStepAuth);
                $twoStepAuth->requestDate = new Carbon();
                $twoStepAuth->save();
            }
        }

        return \view('auth.twostep-verification')->with($data);
    }

    /**
     * Verify the user code input.
     *
     * @throws \Exception
     */
    public function verify(Request $request): ?\Illuminate\Http\JsonResponse
    {
        \abort_if(! \config('auth.TwoStepEnabled'), 404);

        if ($request->ajax()) {
            $validator = \validator($request->all(), [
                'v_input_1' => 'required|min:1|max:1',
                'v_input_2' => 'required|min:1|max:1',
                'v_input_3' => 'required|min:1|max:1',
                'v_input_4' => 'required|min:1|max:1',
            ]);

            if ($validator->fails()) {
                $returnData = $this->invalidCodeReturnData($validator->errors());

                return \response()->json($returnData, 418);
            }

            $code = $request->v_input_1.$request->v_input_2.$request->v_input_3.$request->v_input_4;
            $validCode = $this->twoStepAuth->authCode;

            if ($validCode != $code) {
                $returnData = $this->invalidCodeReturnData();

                return \response()->json($returnData, 418);
            }

            $this->resetActivationCountdown($this->twoStepAuth);

            $returnData = [
                'nextUri' => \session('nextUri', '/'),
                'message' => \trans('auth.titlePassed'),
            ];

            return \response()->json($returnData, 200);
        }

        \abort(404);
    }

    /**
     * Resend the validation code triggered by user.
     */
    public function resend(): \Illuminate\Http\JsonResponse
    {
        \abort_if(! \config('auth.TwoStepEnabled'), 404);

        $twoStepAuth = $this->twoStepAuth;
        $this->sendVerificationCodeNotification($twoStepAuth);

        $returnData = [
            'title'   => \trans('auth.verificationEmailSuccess'),
            'message' => \trans('auth.verificationEmailSentMsg'),
        ];

        return \response()->json($returnData, 200);
    }
}

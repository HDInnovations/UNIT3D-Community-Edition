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

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Translation\Translator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Session\SessionManager;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Controllers\Controller;
use App\Traits\TwoStep;
use Carbon\Carbon;
use Illuminate\Http\Request;

final class TwoStepController extends Controller
{
    use TwoStep;

    /**
     * @var float|int
     */
    private $_authCount;

    private $_authStatus;

    private $_twoStepAuth;

    /**
     * @var float|int
     */
    private $_remainingAttempts;

    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    private ?Authenticatable $_user;
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    private $guard;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $configRepository;
    /**
     * @var \Illuminate\Translation\Translator
     */
    private $translator;
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private $viewFactory;
    /**
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    private $responseFactory;
    /**
     * @var \Illuminate\Session\SessionManager
     */
    private $sessionManager;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Guard $guard, Repository $configRepository, Translator $translator, Factory $viewFactory, ResponseFactory $responseFactory, SessionManager $sessionManager)
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->setUser2StepData();

            return $next($request);
        });
        $this->guard = $guard;
        $this->configRepository = $configRepository;
        $this->translator = $translator;
        $this->viewFactory = $viewFactory;
        $this->responseFactory = $responseFactory;
        $this->sessionManager = $sessionManager;
    }

    /**
     * Set the User2Step Variables.
     *
     * @return void
     */
    private function setUser2StepData(): void
    {
        $user = $this->guard->user();
        $twoStepAuth = $this->getTwoStepAuthStatus($user->id);
        $authCount = $twoStepAuth->authCount;
        $this->_user = $user;
        $this->_twoStepAuth = $twoStepAuth;
        $this->_authCount = $authCount;
        $this->_authStatus = $twoStepAuth->authStatus;
        $this->_remainingAttempts = $this->configRepository->get('auth.TwoStepExceededCount') - $authCount;
    }

    /**
     * Validation and Invalid code failed actions and return message.
     *
     * @param array $errors (optional)
     * @return mixed[]
     */
    private function invalidCodeReturnData(array $errors = null): array
    {
        $this->_authCount = ++$this->_twoStepAuth->authCount;
        $this->_twoStepAuth->save();

        $returnData = [
            'message'           => $this->translator->trans('auth.titleFailed'),
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
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function showVerification(): View
    {
        if (! $this->configRepository->get('auth.TwoStepEnabled')) {
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

        if ($this->_authCount > $this->configRepository->get('auth.TwoStepExceededCount')) {
            $exceededTimeDetails = $this->exceededTimeParser($twoStepAuth->updated_at);

            $data['timeUntilUnlock'] = $exceededTimeDetails['tomorrow'];
            $data['timeCountdownUnlock'] = $exceededTimeDetails['remaining'];

            return $this->viewFactory->make('auth.twostep-exceeded')->with($data);
        }

        $now = new Carbon();
        $sentTimestamp = $twoStepAuth->requestDate;

        if (! $twoStepAuth->authCode) {
            $twoStepAuth->authCode = $this->generateCode();
            $twoStepAuth->save();
        }

        if (! $sentTimestamp) {
            $this->sendVerificationCodeNotification($twoStepAuth);
        } else {
            $timeBuffer = $this->configRepository->get('laravel2step.laravel2stepTimeResetBufferSeconds');
            $timeAllowedToSendCode = $sentTimestamp->addSeconds($timeBuffer);
            if ($now->gt($timeAllowedToSendCode)) {
                $this->sendVerificationCodeNotification($twoStepAuth);
                $twoStepAuth->requestDate = new Carbon();
                $twoStepAuth->save();
            }
        }

        return $this->viewFactory->make('auth.twostep-verification')->with($data);
    }

    /**
     * Verify the user code input.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request): JsonResponse
    {
        if (! $this->configRepository->get('auth.TwoStepEnabled')) {
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

                return $this->responseFactory->json($returnData, 418);
            }

            $code = $request->v_input_1.$request->v_input_2.$request->v_input_3.$request->v_input_4;
            $validCode = $this->_twoStepAuth->authCode;

            if ($validCode != $code) {
                $returnData = $this->invalidCodeReturnData();

                return $this->responseFactory->json($returnData, 418);
            }

            $this->resetActivationCountdown($this->_twoStepAuth);

            $returnData = [
                'nextUri' => $this->sessionManager->get('nextUri', '/'),
                'message' => $this->translator->trans('auth.titlePassed'),
            ];

            return $this->responseFactory->json($returnData, 200);
        } else {
            abort(404);
        }
    }

    /**
     * Resend the validation code triggered by user.
     *
     * @return \Illuminate\Http\Response
     */
    public function resend(): JsonResponse
    {
        if (! $this->configRepository->get('auth.TwoStepEnabled')) {
            abort(404);
        }

        $twoStepAuth = $this->_twoStepAuth;
        $this->sendVerificationCodeNotification($twoStepAuth);

        $returnData = [
            'title'   => $this->translator->trans('auth.verificationEmailSuccess'),
            'message' => $this->translator->trans('auth.verificationEmailSentMsg'),
        ];

        return $this->responseFactory->json($returnData, 200);
    }
}

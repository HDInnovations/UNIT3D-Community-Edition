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

namespace App\Traits;

use App\Models\TwoStepAuth;
use App\Notifications\TwoStepAuthCode;
use Carbon\Carbon;

trait TwoStep
{
    /**
     * Check if the user is authorized.
     *
     * @return bool
     */
    private function twoStepVerification()
    {
        $user = auth()->user();
        if ($user) {
            $twoStepAuthStatus = $this->checkTwoStepAuthStatus($user->id);
            if ($twoStepAuthStatus->authStatus !== true) {
                return false;
            }
            if ($this->checkTimeSinceVerified($twoStepAuthStatus)) {
                return false;
            }

            return true;
        }

        return true;
    }

    /**
     * Check time since user was last verified and take apprpriate action.
     *
     * @param collection $twoStepAuth
     *
     * @return bool
     */
    private function checkTimeSinceVerified($twoStepAuth)
    {
        $expireMinutes = config('auth.TwoStepVerifiedLifetimeMinutes');
        $now = Carbon::now();
        $expire = Carbon::parse($twoStepAuth->authDate)->addMinutes($expireMinutes);
        $expired = $now->gt($expire);

        if ($expired) {
            $this->resetAuthStatus($twoStepAuth);

            return true;
        }

        return false;
    }

    /**
     * Reset TwoStepAuth collection item and code.
     *
     * @param collection $twoStepAuth
     *
     * @return collection
     */
    private function resetAuthStatus($twoStepAuth)
    {
        $twoStepAuth->authCode = $this->generateCode();
        $twoStepAuth->authCount = 0;
        $twoStepAuth->authStatus = 0;
        $twoStepAuth->authDate = null;
        $twoStepAuth->requestDate = null;

        $twoStepAuth->save();

        return $twoStepAuth;
    }

    /**
     * Generate Authorization Code.
     *
     * @param int    $length
     * @param string $prefix
     * @param string $suffix
     *
     * @throws \Exception
     *
     * @return string
     */
    private function generateCode(int $length = 4, string $prefix = '', string $suffix = '')
    {
        for ($i = 0; $i < $length; $i++) {
            $prefix .= random_int(0, 1) ? chr(random_int(65, 90)) : random_int(0, 9);
        }

        return $prefix.$suffix;
    }

    /**
     * Create/retreive 2step verification object.
     *
     * @param int $userId
     *
     * @return collection
     */
    private function checkTwoStepAuthStatus(int $userId)
    {
        return TwoStepAuth::firstOrCreate(
            [
                'userId' => $userId,
            ],
            [
                'userId'    => $userId,
                'authCode'  => $this->generateCode(),
                'authCount' => 0,
            ]
        );
    }

    /**
     * Retreive the Verification Status.
     *
     * @param int $userId
     *
     * @return collection || void
     */
    protected function getTwoStepAuthStatus(int $userId)
    {
        return TwoStepAuth::where('userId', '=', $userId)->firstOrFail();
    }

    /**
     * Format verification exceeded timings with Carbon.
     *
     * @param string $time
     *
     * @return collection
     */
    protected function exceededTimeParser($time)
    {
        $tomorrow = Carbon::parse($time)->addMinutes(config('auth.TwoStepExceededCountdownMinutes'))->format('l, F jS Y h:i:sa');
        $remaining = $time->addMinutes(config('auth.TwoStepExceededCountdownMinutes'))->diffForHumans(null, true);

        $data = [
            'tomorrow'  => $tomorrow,
            'remaining' => $remaining,
        ];

        return collect($data);
    }

    /**
     * Check if time since account lock has expired and return true if account verification can be reset.
     *
     * @param datetime $time
     *
     * @return bool
     */
    protected function checkExceededTime($time)
    {
        $now = Carbon::now();
        $expire = Carbon::parse($time)->addMinutes(config('auth.TwoStepExceededCountdownMinutes'));
        $expired = $now->gt($expire);

        if ($expired) {
            return true;
        }

        return false;
    }

    /**
     * Method to reset code and count.
     *
     * @param collection $twoStepEntry
     *
     * @return collection
     */
    protected function resetExceededTime($twoStepEntry)
    {
        $twoStepEntry->authCount = 0;
        $twoStepEntry->authCode = $this->generateCode();
        $twoStepEntry->save();

        return $twoStepEntry;
    }

    /**
     * Successful activation actions.
     *
     * @param collection $twoStepAuth
     *
     * @return void
     */
    protected function resetActivationCountdown($twoStepAuth)
    {
        $twoStepAuth->authCode = $this->generateCode();
        $twoStepAuth->authCount = 0;
        $twoStepAuth->authStatus = 1;
        $twoStepAuth->authDate = Carbon::now();
        $twoStepAuth->requestDate = null;

        $twoStepAuth->save();
    }

    /**
     * Send verification code via notify.
     *
     * @param $twoStepAuth
     * @param string $deliveryMethod (nullable)
     *
     * @return void
     */
    protected function sendVerificationCodeNotification($twoStepAuth, $deliveryMethod = null)
    {
        $user = auth()->user();
        if ($deliveryMethod === null) {
            $user->notify(new TwoStepAuthCode($user, $twoStepAuth->authCode));
        }
        $twoStepAuth->requestDate = Carbon::now();

        $twoStepAuth->save();
    }
}

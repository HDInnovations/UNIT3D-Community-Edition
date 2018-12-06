<?php

namespace App\Http\Controllers\Auth;

use App\Group;
use App\UserActivation;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function resetPassword($user, $password)
    {
        $validatingGroup = Group::where('slug', '=', 'validating')->select('id')->first();
        $memberGroup = Group::where('slug', '=', 'user')->select('id')->first();
        $user->password = bcrypt($password);
        $user->remember_token = Str::random(60);

        if ($user->group_id === $validatingGroup->id) {
            $user->group_id = $memberGroup->id;
        }

        $user->active = true;
        $user->save();

        // Activity Log
        \LogActivity::addToLog('Member '.$user->username.' has successfully reset his/her password.');

        UserActivation::where('user_id', $user->id)->delete();

        $this->guard()->login($user);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\UserActivation;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Str;
use App\Group;

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
        $validatingGroup = Group::where('slug', '=', 'validating')->pluck('id');
        $memberGroup = Group::where('slug', '=', 'member')->pluck('id');
        $user->password = bcrypt($password);
        $user->remember_token = Str::random(60);

        if ($user->group_id === $validatingGroup) {
            $user->group_id = $memberGroup;
        }

        $user->active = true;
        $user->save();

        // Activity Log
        \LogActivity::addToLog("Member " . $user->username . " has successfully reset his/her password.");

        UserActivation::where('user_id', $user->id)->delete();

        $this->guard()->login($user);
    }
}

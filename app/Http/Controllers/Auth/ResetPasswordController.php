<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\UserActivation;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/';
    protected $group_id = 3;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function resetPassword($user, $password)
    {
        $user->password = bcrypt($password);
        $user->remember_token = Str::random(60);
        $user->group_id = $this->group_id;
        $user->active = true;
        $user->save();

        // Activity Log
        \LogActivity::addToLog("Member " . $user->username . " has successfully reset his/her password.");

        UserActivation::where('user_id', $user->id)->delete();

        $this->guard()->login($user);
    }
}

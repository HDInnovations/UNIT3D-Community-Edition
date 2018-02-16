<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Notifications\UsernameReminder;
use \Toastr;

class ForgotUsernameController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validator = Validator::make(
            $data,
            ['email' => 'required|email'],
            ['email.required' => 'Email is required', 'email.email' => 'Email is invalid']
        );

        return $validator;
    }

    public function showForgotUsernameForm()
    {
        return view('auth.username');
    }

    public function sendUserameReminder(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request,
                $validator
            );
        }

        $email  = $request->get('email');

        // get the user associated to this activation key
        $user = User::where('email', $email)->first();

        if (empty($user)) {
            return redirect()->route('username.request')->with(Toastr::error('We could not find this email in our system!', 'Whoops!', ['options']));
        }

        //send username reminder notification
        $user->notify(new UsernameReminder());

        return redirect()->route('login')->with(Toastr::success('Your username has been sent to your email address!', 'Yay!', ['options']));
    }
}

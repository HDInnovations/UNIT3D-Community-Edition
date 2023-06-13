<?php

namespace App\Actions\Fortify;

use App\Models\Group;
use App\Models\Invite;
use App\Models\PrivateMessage;
use App\Models\User;
use App\Repositories\ChatRepository;
use App\Rules\EmailBlacklist;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param array<string, string> $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'username' => 'required|alpha_dash|string|between:3,25|unique:users',
            'password' => [
                'required',
                'confirmed',
                $this->passwordRules(),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:70',
                'unique:users',
                Rule::when(config('email-blacklist.enabled') === true, fn () => new EmailBlacklist()),
            ],
            'captcha' => [
                Rule::excludeIf(config('captcha.enabled') === false),
                Rule::when(config('captcha.enabled') === true, 'hiddencaptcha'),
            ],
            'code' => 'required',
        ])->validate();

        // Make sure open reg is off and invite code exists and has not been used already

        $invite = Invite::query()->where('code', '=', $input['code'])->first();

        if (config('other.invite-only') === true && ($invite === null || $invite->accepted_by !== null)) {
            return to_route('registrationForm', ['code' => $input['code']])
                ->withErrors(trans('auth.invalid-key'));
        }

        $validatingGroup = cache()->rememberForever('validating_group', fn () => Group::query()->where('slug', '=', 'validating')->pluck('id'));

        $user = User::create([
            'username'   => $input['username'],
            'email'      => $input['email'],
            'password'   => Hash::make($input['password']),
            'passkey'    => md5(random_bytes(60)),
            'rsskey'     => md5(random_bytes(60)),
            'uploaded'   => config('other.default_upload'),
            'downloaded' => config('other.default_download'),
            'style'      => config('other.default_style', 0),
            'locale'     => config('app.locale'),
            'group_id'   => $validatingGroup[0],
        ]);

        if ($invite !== null) {
            $invite->update([
                'accepted_by' => $user->id,
                'accepted_at' => new Carbon(),
            ]);
        }

        // Select A Random Welcome Message

        $profileUrl = href_profile($user);

        $welcomeArray = [
            sprintf('[url=%s]%s[/url], Welcome to ', $profileUrl, $user->username).config('other.title').'! Hope you enjoy the community :rocket:',
            sprintf("[url=%s]%s[/url], We've been expecting you :space_invader:", $profileUrl, $user->username),
            sprintf("[url=%s]%s[/url] has arrived. Party's over. :cry:", $profileUrl, $user->username),
            sprintf("It's a bird! It's a plane! Nevermind, it's just [url=%s]%s[/url].", $profileUrl, $user->username),
            sprintf('Ready player [url=%s]%s[/url].', $profileUrl, $user->username),
            sprintf('A wild [url=%s]%s[/url] appeared.', $profileUrl, $user->username),
            'Welcome to '.config('other.title').sprintf(' [url=%s]%s[/url]. We were expecting you ( ͡° ͜ʖ ͡°)', $profileUrl, $user->username),
        ];

        $this->chatRepository->systemMessage(
            $welcomeArray[array_rand($welcomeArray)]
        );

        // Send Welcome PM

        PrivateMessage::create([
            'sender_id'   => 1,
            'receiver_id' => $user->id,
            'subject'     => config('welcomepm.subject'),
            'message'     => config('welcomepm.message'),
        ]);

        return to_route('login')
            ->withSuccess(trans('auth.register-thanks'));
    }
}

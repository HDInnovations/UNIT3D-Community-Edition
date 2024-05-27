<?php

declare(strict_types=1);

/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GeneralSettingController extends Controller
{
    /**
     * Update user general settings.
     */
    public function update(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user), 403);

        $settings = $user->settings;

        if ($settings === null) {
            $settings = new UserSetting();
            $settings->user_id = $user->id;
        }

        $request->validate([
            'censor'         => 'required|boolean',
            'chat_hidden'    => 'required|boolean',
            'locale'         => ['required', Rule::in(array_keys(Language::allowed()))],
            'style'          => 'required|numeric',
            'custom_css'     => 'nullable|url',
            'standalone_css' => 'nullable|url',
            'torrent_layout' => ['required', Rule::in([0, 1, 2, 3])],
            'show_poster'    => 'required|boolean',
        ]);

        // General Settings
        $settings->censor = $request->censor;
        $settings->chat_hidden = $request->chat_hidden;
        $settings->locale = $request->input('locale');
        $settings->style = $request->style;
        $settings->custom_css = $request->custom_css;
        $settings->standalone_css = $request->standalone_css;
        $settings->torrent_layout = $request->torrent_layout;
        $settings->show_poster = $request->show_poster;

        $settings->save();

        cache()->forget('user-settings:by-user-id:'.$user->id);

        return to_route('users.general_settings.edit', ['user' => $user])
            ->withSuccess('Your general settings have been successfully saved.');
    }

    /**
     * Edit user general settings.
     */
    public function edit(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        abort_unless($request->user()->is($user), 403);

        return view('user.general_setting.edit', ['user' => $user]);
    }
}

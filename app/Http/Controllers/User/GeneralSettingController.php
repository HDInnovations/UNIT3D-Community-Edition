<?php
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
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GeneralSettingController extends Controller
{
    /**
     * Update user general settings.
     */
    public function update(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        \abort_unless($request->user()->id == $user->id, 403);

        $request->validate([
            'censor'         => 'required|boolean',
            'chat_hidden'    => 'required|boolean',
            'locale'         => ['required', Rule::in(\array_keys(Language::allowed()))],
            'style'          => 'required|numeric',
            'custom_css'     => 'nullable|url',
            'standalone_css' => 'nullable|url',
            'torrent_layout' => ['required', Rule::in([0])],
            'show_poster'    => 'required|boolean',
            'ratings'        => ['required', Rule::in([0, 1])],
        ]);

        // General Settings
        $user->censor = $request->censor;
        $user->chat_hidden = $request->chat_hidden;
        $user->locale = $request->input('locale');
        $user->style = $request->style;
        $user->custom_css = $request->custom_css;
        $user->standalone_css = $request->standalone_css;
        $user->torrent_layout = $request->torrent_layout;
        $user->show_poster = $request->show_poster;
        $user->ratings = $request->ratings;
        $user->save();

        return \to_route('users.general_settings.edit', ['user' => $user])
            ->withSuccess('Your general settings have been successfully saved.');
    }

    /**
     * Edit user general settings.
     */
    public function edit(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        \abort_unless($request->user()->id == $user->id, 403);

        return \view('user.general_setting.edit', ['user' => $user]);
    }
}

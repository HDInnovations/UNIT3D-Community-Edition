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

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\MediaLanguage;
use Illuminate\Http\Request;

class MediaLanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $mediaLanguages = MediaLanguage::all()->sortBy('name');

        return \view('Staff.media_language.index', ['media_languages' => $mediaLanguages]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('Staff.media_language.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $mediaLanguage = new MediaLanguage();
        $mediaLanguage->name = $request->input('name');
        $mediaLanguage->code = $request->input('code');

        $v = \validator($mediaLanguage->toArray(), [
            'name' => 'required|unique:media_languages',
            'code' => 'required|unique:media_languages',
        ]);

        if ($v->fails()) {
            return \to_route('staff.media-languages.index')
                ->withErrors($v->errors());
        }

        $mediaLanguage->save();

        return \to_route('staff.media-languages.index')
            ->withSuccess('Media Language Successfully Added');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MediaLanguage $mediaLanguage): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('Staff.media_language.edit', ['media_language' => $mediaLanguage]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MediaLanguage $mediaLanguage): \Illuminate\Http\RedirectResponse
    {
        $mediaLanguage->name = $request->input('name');
        $mediaLanguage->code = $request->input('code');

        $v = \validator($mediaLanguage->toArray(), [
            'name' => 'required',
            'code' => 'required',
        ]);

        if ($v->fails()) {
            return \to_route('staff.media-languages.index')
                ->withErrors($v->errors());
        }

        $mediaLanguage->save();

        return \to_route('staff.media-languages.index')
            ->withSuccess('Media Language Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \Exception
     */
    public function destroy(MediaLanguage $mediaLanguage): \Illuminate\Http\RedirectResponse
    {
        $mediaLanguage->delete();

        return \to_route('staff.media-languages.index')
            ->withSuccess('Media Language Has Successfully Been Deleted');
    }
}

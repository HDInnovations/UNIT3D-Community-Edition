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

namespace App\Http\Controllers;

use App\Models\Torrent;
use App\Models\Subtitle;
use Illuminate\Http\Request;
use App\Models\MediaLanguage;

class SubtitleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Torrent  $torrent_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($torrent_id)
    {
        $torrent = Torrent::findOrFail($torrent_id);
        $media_languages = MediaLanguage::all()->sortBy('name');

        return view('subtitle.create', ['torrent' => $torrent, 'media_languages' => $media_languages]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $subtitle_file = $request->file('subtitle_file');
        $filename = uniqid().'.srt';

        $subtitle = new Subtitle();
        $subtitle->title = $subtitle_file->getClientOriginalName();
        $subtitle->file_name = $filename;
        $subtitle->file_size = $subtitle_file->getSize();
        $subtitle->extension = $subtitle_file->getClientOriginalExtension();
        $subtitle->language_id = $request->input('language_id');
        $subtitle->note =$request->input('note');
        $subtitle->downloads = 0;
        $subtitle->verified = 0;
        $subtitle->user_id = $user->id;
        $subtitle->torrent_id = $request->input('torrent_id');
        $subtitle->status = 1;
        $subtitle->moderated_at = now();
        $subtitle->moderated_by = 1;

        $v = validator($subtitle->toArray(), [
            'title'       => 'required',
            'file_name'   => 'required',
            'file_size'   => 'required',
            'extension'   => 'required',
            'language_id' => 'required',
            'user_id'     => 'required',
            'torrent_id'  => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('subtitles.create', ['torrent_id' => $request->input('torrent_id')])
                ->withErrors($v->errors());
        }
        $subtitle->save();

        file_put_contents(getcwd().'/files/subtitles/'.$filename, $subtitle_file);

        return redirect()->route('torrent', ['id' => $request->input('torrent_id')])
            ->withSuccess('Subtitle Successfully Added');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subtitle      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $subtitle = Subtitle::findOrFail($id);

        $user = $request->user();
        abort_unless($user->group->is_modo || $user->id == $subtitle->user_id, 403);

        $subtitle->language_id = $request->input('language_id');
        $subtitle->note =$request->input('note');

        $v = validator($subtitle->toArray(), [
            'language_id' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('torrent', ['id' => $request->input('torrent_id')])
                ->withErrors($v->errors());
        }
        $subtitle->save();

        return redirect()->route('torrent', ['id' => $request->input('torrent_id')])
            ->withSuccess('Subtitle Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subtitle     $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $subtitle = Subtitle::findOrFail($id);

        $user = $request->user();
        abort_unless($user->group->is_modo || $user->id == $subtitle->user_id, 403);

        $subtitle->delete();

        return redirect()->route('torrent', ['id' => $request->input('torrent_id')])
            ->withSuccess('Subtitle Successfully Deleted');
    }

    /**
     * Download the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subtitle     $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Request $request, $id)
    {
        $subtitle = Subtitle::findOrFail($id);
        $user = $request->user();

        // User's download rights are revoked
        if ($user->can_download == 0 && $subtitle->user_id != $user->id) {
            return redirect()->route('torrent', ['id' => $subtitle->torrent->id])
                ->withErrors('Your Download Rights Have Been Revoked!');
        }

        // Grab the subtitle file
        $subtitle_file = file_get_contents(public_path().'/files/subtitles/'.$subtitle->file_name);

        // Define the filename for the download
        $temp_filename = '['.$subtitle->language->name.' Subtitle]'.$subtitle->torrent->name.'.srt';

        // Delete the last torrent tmp file
        if (file_exists(public_path().'/files/tmp/'.$temp_filename)) {
            unlink(public_path().'/files/tmp/'.$temp_filename);
        }

        // Place temp file
        file_put_contents(public_path().'/files/tmp/'.$temp_filename, $subtitle_file);

        // Increment doownloads count
        $subtitle->downloads = ++$subtitle->downloads;
        $subtitle->save();

        return response()->download(public_path('files/tmp/'.$temp_filename))->deleteFileAfterSend(true);
    }
}

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

use Illuminate\Support\Facades\Storage;
use App\Achievements\UserUploaded1000Subtitles;
use App\Achievements\UserUploaded100Subtitles;
use App\Achievements\UserUploaded200Subtitles;
use App\Achievements\UserUploaded25Subtitles;
use App\Achievements\UserUploaded300Subtitles;
use App\Achievements\UserUploaded400Subtitles;
use App\Achievements\UserUploaded500Subtitles;
use App\Achievements\UserUploaded50Subtitles;
use App\Achievements\UserUploaded600Subtitles;
use App\Achievements\UserUploaded700Subtitles;
use App\Achievements\UserUploaded800Subtitles;
use App\Achievements\UserUploaded900Subtitles;
use App\Achievements\UserUploadedFirstSubtitle;
use App\Models\MediaLanguage;
use App\Models\Subtitle;
use App\Models\Torrent;
use App\Repositories\ChatRepository;
use Illuminate\Http\Request;

class SubtitleController extends Controller
{
    /**
     * @var ChatRepository
     */
    private $chat;

    /**
     * SubtitleController Constructor.
     *
     * @param ChatRepository $chat
     */
    public function __construct(ChatRepository $chat)
    {
        $this->chat = $chat;
    }

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
     * @param \App\Models\Torrent $torrent_id
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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $subtitle_file = $request->file('subtitle_file');
        $filename = uniqid().'.'.$subtitle_file->getClientOriginalExtension();

        $subtitle = new Subtitle();
        $subtitle->title = $subtitle_file->getClientOriginalName();
        $subtitle->file_name = $filename;
        $subtitle->file_size = $subtitle_file->getSize();
        $subtitle->extension = '.'.$subtitle_file->getClientOriginalExtension();
        $subtitle->language_id = $request->input('language_id');
        $subtitle->note = $request->input('note');
        $subtitle->downloads = 0;
        $subtitle->verified = 0;
        $subtitle->user_id = $user->id;
        $subtitle->anon = $request->input('anonymous');
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

        // Save Subtitle
        Storage::disk('subtitles')->put($filename, file_get_contents($subtitle_file));
        $subtitle->save();

        // Announce To Shoutbox
        $torrent_url = hrefTorrent($subtitle->torrent);
        $profile_url = hrefProfile($user);
        if ($subtitle->anon == false) {
            $this->chat->systemMessage(
                sprintf('[url=%s]%s[/url] has uploaded a new %s subtitle for [url=%s]%s[/url]', $profile_url, $user->username, $subtitle->language->name, $torrent_url, $subtitle->torrent->name)
            );
        } else {
            $this->chat->systemMessage(
                sprintf('An anonymous user has uploaded a new %s subtitle for [url=%s]%s[/url]', $subtitle->language->name, $torrent_url, $subtitle->torrent->name)
            );
        }

        // Achievements
        $user->unlock(new UserUploadedFirstSubtitle(), 1);
        $user->addProgress(new UserUploaded25Subtitles(), 1);
        $user->addProgress(new UserUploaded50Subtitles(), 1);
        $user->addProgress(new UserUploaded100Subtitles(), 1);
        $user->addProgress(new UserUploaded200Subtitles(), 1);
        $user->addProgress(new UserUploaded300Subtitles(), 1);
        $user->addProgress(new UserUploaded400Subtitles(), 1);
        $user->addProgress(new UserUploaded500Subtitles(), 1);
        $user->addProgress(new UserUploaded600Subtitles(), 1);
        $user->addProgress(new UserUploaded700Subtitles(), 1);
        $user->addProgress(new UserUploaded800Subtitles(), 1);
        $user->addProgress(new UserUploaded900Subtitles(), 1);
        $user->addProgress(new UserUploaded1000Subtitles(), 1);

        return redirect()->route('torrent', ['id' => $request->input('torrent_id')])
            ->withSuccess('Subtitle Successfully Added');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subtitle     $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $subtitle = Subtitle::findOrFail($id);

        $user = $request->user();
        abort_unless($user->group->is_modo || $user->id == $subtitle->user_id, 403);

        $subtitle->language_id = $request->input('language_id');
        $subtitle->note = $request->input('note');

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

        unlink(public_path().'/files/subtitles/'.$subtitle->file_name);

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

        // Define the filename for the download
        $temp_filename = '['.$subtitle->language->name.' Subtitle]'.$subtitle->torrent->name.$subtitle->extension;

        // Delete the last torrent tmp file
        if (file_exists(public_path().'/files/tmp/'.$temp_filename)) {
            unlink(public_path().'/files/tmp/'.$temp_filename);
        }

        // Grab the subtitle file
        Storage::copy(public_path().'/files/subtitles/'.$subtitle->file_name, public_path().'/files/tmp/'.$temp_filename);

        // Increment downloads count
        $subtitle->downloads = ++$subtitle->downloads;
        $subtitle->save();

        $headers = ['Content-Type: application/zip'];

        if ($subtitle->extension === '.zip') {
            return response()->download(public_path('files/tmp/'.$temp_filename), $temp_filename, $headers)->deleteFileAfterSend(true);
        } else {
            return response()->download(public_path('files/tmp/'.$temp_filename))->deleteFileAfterSend(true);
        }
    }
}

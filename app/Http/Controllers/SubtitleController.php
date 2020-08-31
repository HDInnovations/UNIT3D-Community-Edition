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
use App\Models\Category;
use App\Models\MediaLanguage;
use App\Models\Subtitle;
use App\Models\Torrent;
use App\Repositories\ChatRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubtitleController extends Controller
{
    /**
     * @var ChatRepository
     */
    private $chatRepository;

    /**
     * SubtitleController Constructor.
     *
     * @param \App\Repositories\ChatRepository $chatRepository
     */
    public function __construct(ChatRepository $chatRepository)
    {
        $this->chatRepository = $chatRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $subtitles = Subtitle::with(['user', 'torrent', 'language'])->latest()->paginate(50);
        $media_languages = MediaLanguage::all()->sortBy('name');
        $categories = Category::all()->sortBy('position');

        return \view('subtitle.index', ['subtitles' => $subtitles, 'media_languages' => $media_languages, 'categories' => $categories]);
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

        return \view('subtitle.create', ['torrent' => $torrent, 'media_languages' => $media_languages]);
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
        $filename = \uniqid().'.'.$subtitle_file->getClientOriginalExtension();

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
        $subtitle->moderated_at = \now();
        $subtitle->moderated_by = 1;

        $v = \validator($subtitle->toArray(), [
            'title'       => 'required',
            'file_name'   => 'required',
            'file_size'   => 'required',
            'extension'   => 'required',
            'language_id' => 'required',
            'user_id'     => 'required',
            'torrent_id'  => 'required',
        ]);

        if ($v->fails()) {
            return \redirect()->route('subtitles.create', ['torrent_id' => $request->input('torrent_id')])
                ->withErrors($v->errors());
        }

        // Save Subtitle
        Storage::disk('subtitles')->put($filename, \file_get_contents($subtitle_file));
        $subtitle->save();

        // Announce To Shoutbox
        $torrent_url = \href_torrent($subtitle->torrent);
        $profile_url = \href_profile($user);
        if ($subtitle->anon == false) {
            $this->chatRepository->systemMessage(
                \sprintf('[url=%s]%s[/url] has uploaded a new %s subtitle for [url=%s]%s[/url]', $profile_url, $user->username, $subtitle->language->name, $torrent_url, $subtitle->torrent->name)
            );
        } else {
            $this->chatRepository->systemMessage(
                \sprintf('An anonymous user has uploaded a new %s subtitle for [url=%s]%s[/url]', $subtitle->language->name, $torrent_url, $subtitle->torrent->name)
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

        return \redirect()->route('torrent', ['id' => $request->input('torrent_id')])
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
        \abort_unless($user->group->is_modo || $user->id == $subtitle->user_id, 403);

        $subtitle->language_id = $request->input('language_id');
        $subtitle->note = $request->input('note');

        $v = \validator($subtitle->toArray(), [
            'language_id' => 'required',
        ]);

        if ($v->fails()) {
            return \redirect()->route('torrent', ['id' => $request->input('torrent_id')])
                ->withErrors($v->errors());
        }
        $subtitle->save();

        return \redirect()->route('torrent', ['id' => $request->input('torrent_id')])
            ->withSuccess('Subtitle Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subtitle     $id
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $subtitle = Subtitle::findOrFail($id);

        $user = $request->user();
        \abort_unless($user->group->is_modo || $user->id == $subtitle->user_id, 403);

        if (Storage::disk('subtitles')->exists($subtitle->file_name)) {
            Storage::disk('subtitles')->delete($subtitle->file_name);
        }

        $subtitle->delete();

        return \redirect()->route('torrent', ['id' => $request->input('torrent_id')])
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
            return \redirect()->route('torrent', ['id' => $subtitle->torrent->id])
                ->withErrors('Your Download Rights Have Been Revoked!');
        }

        // Define the filename for the download
        $temp_filename = '['.$subtitle->language->name.' Subtitle]'.$subtitle->torrent->name.$subtitle->extension;

        // Increment downloads count
        $subtitle->increment('downloads');

        $headers = ['Content-Type: '.Storage::disk('subtitles')->mimeType($subtitle->file_name)];

        return Storage::disk('subtitles')->download($subtitle->file_name, $temp_filename, $headers);
    }

    /**
     * Uses Input's To Put Together A Search.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subtitle     $subtitle
     *
     * @throws \Throwable
     *
     * @return array
     */
    public function faceted(Request $request, Subtitle $subtitle)
    {
        $user = $request->user();

        $name = $request->input('name');
        $categories = $request->input('categories');
        $language_id = $request->input('language_id');

        $terms = \explode(' ', $name);
        $name = '';
        foreach ($terms as $term) {
            $name .= '%'.$term.'%';
        }

        $subtitle = $subtitle->with(['user', 'torrent', 'language']);

        if ($request->has('name') && $request->input('name') != null) {
            $torrents = Torrent::where('name', 'like', $name)->pluck('id');
            $subtitle->whereIn('torrent_id', $torrents);
        }

        if ($request->has('categories') && $request->input('categories') != null) {
            $torrents = Torrent::whereIn('category_id', $categories)->pluck('id');
            $subtitle->whereIn('torrent_id', $torrents);
        }

        if ($request->has('language_id') && $request->input('language_id') != null) {
            $subtitle->where('language_id', '=', $language_id);
        }

        $subtitles = $subtitle->latest()->paginate(25);

        return \view('subtitle.results', [
            'user'        => $user,
            'subtitles'   => $subtitles,
        ])->render();
    }
}

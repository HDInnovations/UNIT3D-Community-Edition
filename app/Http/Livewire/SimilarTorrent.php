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

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\FeaturedTorrent;
use App\Models\Graveyard;
use App\Models\History;
use App\Models\Movie;
use App\Models\Peer;
use App\Models\PlaylistTorrent;
use App\Models\PrivateMessage;
use App\Models\Subtitle;
use App\Models\Torrent;
use App\Models\TorrentFile;
use App\Models\Tv;
use App\Models\Warning;
use Livewire\Component;
use MarcReichel\IGDBLaravel\Models\Game;

class SimilarTorrent extends Component
{
    public Category $category;

    public Movie|Tv|Game $work;

    public $tmdbId;

    public $igdbId;

    public $reason;

    public $checked = [];

    public bool $selectPage = false;

    public bool $selectAll = false;

    public string $sortField = 'bumped_at';

    public string $sortDirection = 'desc';

    protected $listeners = ['destroy' => 'deleteRecords'];

    final public function updatedSelectPage($value): void
    {
        $this->checked = $value ? $this->torrents->pluck('id')->map(fn ($item) => (string) $item)->toArray() : [];
    }

    final public function selectAll(): void
    {
        $this->selectAll = true;
        $this->checked = $this->torrents->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }

    final public function updatedChecked(): void
    {
        $this->selectPage = false;
    }

    final public function isChecked($torrentId): bool
    {
        return \in_array($torrentId, $this->checked);
    }

    final public function getTorrentsProperty(): \Illuminate\Support\Collection
    {
        $user = auth()->user();

        return Torrent::query()
            ->with('user:id,username,group_id', 'category', 'type', 'resolution')
            ->withCount(['thanks', 'comments'])
            ->withExists([
                'bookmarks'          => fn ($query) => $query->where('user_id', '=', $user->id),
                'history as seeding' => fn ($query) => $query->where('user_id', '=', $user->id)
                    ->where('active', '=', 1)
                    ->where('seeder', '=', 1),
                'history as leeching' => fn ($query) => $query->where('user_id', '=', $user->id)
                    ->where('active', '=', 1)
                    ->where('seeder', '=', 0),
                'history as not_completed' => fn ($query) => $query->where('user_id', '=', $user->id)
                    ->where('active', '=', 0)
                    ->where('seeder', '=', 1)
                    ->whereNull('completed_at'),
                'history as not_seeding' => fn ($query) => $query->where('user_id', '=', $user->id)
                    ->where('active', '=', 0)
                    ->where('seeder', '=', 1)
                    ->whereNotNull('completed_at'),
            ])
            ->when(
                $this->category->movie_meta,
                fn ($query) => $query->whereHas('category', fn ($query) => $query->where('movie_meta', '=', 1)),
            )
            ->when(
                $this->category->tv_meta,
                fn ($query) => $query->whereHas('category', fn ($query) => $query->where('tv_meta', '=', 1)),
            )
            ->when(
                $this->category->tv_meta || $this->category->movie_meta,
                fn ($query) => $query->where('tmdb', '=', $this->tmdbId),
                fn ($query) => $query->where('igdb', '=', $this->igdbId),
            )
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();
    }

    final public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    final public function alertConfirm(): void
    {
        $torrents = Torrent::whereKey($this->checked)->pluck('name')->toArray();
        $names = $torrents;
        $this->dispatchBrowserEvent('swal:confirm', [
            'type'    => 'warning',
            'message' => 'Are you sure?',
            'body'    => 'If deleted, you will not be able to recover the following files!'.nl2br("\n")
                        .nl2br(implode("\n", $names)),
        ]);
    }

    final public function deleteRecords(): void
    {
        $torrents = Torrent::whereKey($this->checked)->get();
        $names = [];
        $users = [];
        $title = match (1) {
            $this->category->movie_meta => ($movie = Movie::find($this->tmdbId))->name.' ('.$movie->release_date.')',
            $this->category->tv_meta    => ($tv = Tv::find($this->tmdbId))->name.' ('.$tv->first_air_date.')',
            $this->category->game_meta  => ($game = Game::find($this->igdbId))->name.' ('.$game->first_release_date.')',
            default                     => $torrents->pluck('name')->join(', '),
        };

        foreach ($torrents as $torrent) {
            $names[] = $torrent->name;
            foreach (History::where('torrent_id', '=', $torrent->id)->get() as $pm) {
                if (! \in_array($pm->user_id, $users)) {
                    $users[] = $pm->user_id;
                }
            }

            // Reset Requests
            $torrent->requests()->update([
                'filled_by'     => null,
                'filled_when'   => null,
                'torrent_id'    => null,
                'approved_by'   => null,
                'approved_when' => null,
            ]);

            //Remove Torrent related info
            cache()->forget(sprintf('torrent:%s', $torrent->info_hash));
            Peer::where('torrent_id', '=', $torrent->id)->delete();
            History::where('torrent_id', '=', $torrent->id)->delete();
            Warning::where('torrent', '=', $torrent->id)->delete();
            TorrentFile::where('torrent_id', '=', $torrent->id)->delete();
            PlaylistTorrent::where('torrent_id', '=', $torrent->id)->delete();
            Subtitle::where('torrent_id', '=', $torrent->id)->delete();
            Graveyard::where('torrent_id', '=', $torrent->id)->delete();
            if ($torrent->featured === 1) {
                FeaturedTorrent::where('torrent_id', '=', $torrent->id)->delete();
            }

            $torrent->delete();
        }


        foreach ($users as $user) {
            $pmuser = new PrivateMessage();
            $pmuser->sender_id = 1;
            $pmuser->receiver_id = $user;
            $pmuser->subject = 'Bulk Torrents Deleted - '.$title.'! ';
            $pmuser->message = '[b]Attention: [/b] The following torrents have been removed from our site.
            [list]
                [*]'.implode(' [*]', $names).'
            [/list]
            Our system shows that you were either the uploader, a seeder or a leecher on said torrent. We just wanted to let you know you can safely remove it from your client.
                                    [b]Removal Reason: [/b] '.$this->reason.'
                                    [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]';
            $pmuser->save();
        }

        $this->checked = [];
        $this->selectAll = false;
        $this->selectPage = false;

        $this->dispatchBrowserEvent('swal:modal', [
            'type'    => 'success',
            'message' => 'Torrents Deleted Successfully!',
            'text'    => 'A personal message has been sent to all users that have downloaded these torrents.',
        ]);
    }

    final public function deleteSingleRecord($torrentId): void
    {
        $torrent = Torrent::findOrFail($torrentId);
        $torrent->delete();
        $this->checked = array_diff($this->checked, [$torrentId]);

        $this->dispatchBrowserEvent('swal:modal', [
            'type'    => 'success',
            'message' => 'Torrent Deleted Successfully!',
            'text'    => 'A personal message has been sent to all users that have downloaded this torrent.',
        ]);
    }

    final public function getPersonalFreeleechProperty()
    {
        return cache()->get('personal_freeleech:'.auth()->user()->id);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.similar-torrent', [
            'user'              => auth()->user(),
            'torrents'          => $this->torrents,
            'personalFreeleech' => $this->personalFreeleech,
        ]);
    }
}

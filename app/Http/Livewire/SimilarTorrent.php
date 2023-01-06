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

class SimilarTorrent extends Component
{
    public $categoryId;

    public $tmdbId;

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
        return in_array($torrentId, $this->checked);
    }

    final public function getTorrentsProperty(): \Illuminate\Support\Collection
    {
        $category = Category::findOrFail($this->categoryId);

        $query = Torrent::query();
        $query = $query->with(['user:id,username,group_id', 'category', 'type', 'resolution'])
            ->withCount(['thanks', 'comments']);
        if ($category->movie_meta) {
            $query = $query->whereHas('category', function ($q) {
                $q->where('movie_meta', '=', true);
            });
        }

        if ($category->tv_meta) {
            $query = $query->whereHas('category', function ($q) {
                $q->where('tv_meta', '=', true);
            });
        }

        $query = $query->where('tmdb', '=', $this->tmdbId);
        $query = $query->orderBy($this->sortField, $this->sortDirection);

        return $query->get();
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
            'body'    => 'If deleted, you will not be able to recover the following files!'.\nl2br("\n")
                        .\nl2br(\implode("\n", $names)),
        ]);
    }

    final public function deleteRecords(): void
    {
        $torrents = Torrent::whereKey($this->checked)->get();
        $names = [];
        $users = [];
        $titleids = [];
        $titles = [];
        foreach ($torrents as $torrent) {
            $names[] = $torrent->name;
            foreach (History::where('torrent_id', '=', $torrent->id)->get() as $pm) {
                if (! in_array($pm->user_id, $users)) {
                    $users[] = $pm->user_id;
                }
            }

            if (! in_array($torrent->tmdb, $titleids)) {
                $titleids[] = $torrent->tmdb;
                $title = null;
                $cat = $torrent->category;
                $meta = 'none';

                if ($cat->tv_meta === 1) {
                    $meta = 'tv';
                } elseif ($cat->movie_meta === 1) {
                    $meta = 'movie';
                }

                switch ($meta) {
                    case 'movie':
                        $title = Movie::find($torrent->tmdb);
                        $titles[] = $title->title.' ('.substr($title->release_date, 0, 4).')';
                        break;
                    case 'tv':
                        $title = Tv::find($torrent->tmdb);
                        $titles[] = $title->name.' ('.substr($title->first_air_date, 0, 4).')';
                        break;
                    default:
                        break;
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
            \cache()->forget(\sprintf('torrent:%s', $torrent->info_hash));
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
            $pmuser->subject = 'Bulk Torrents Deleted - '.\implode(', ', $titles).'! ';
            $pmuser->message = '[b]Attention: [/b] The following torrents have been removed from our site.
            [list]
                [*]'.\implode(' [*]', $names).'
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
        return \cache()->rememberForever(
            'personal_freeleech:'.\auth()->user()->id,
            fn () => \auth()->user()->personalFreeleeches()->exists()
        );
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return \view('livewire.similar-torrent', [
            'user'              => \auth()->user(),
            'torrents'          => $this->torrents,
            'personalFreeleech' => $this->personalFreeleech,
        ]);
    }
}

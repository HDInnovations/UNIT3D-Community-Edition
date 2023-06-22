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

use App\Models\Torrent;
use Livewire\Component;

class SmallBookmarkButton extends Component
{
    public Torrent $torrent;

    public bool $isBookmarked;

    final public function store(): void
    {
        if (auth()->user()->bookmarks()->where('torrent_id', '=', $this->torrent->id)->exists()) {
            $this->dispatchBrowserEvent('error', ['type' => 'error',  'message' => 'Torrent Has Already Been Bookmarked!']);

            return;
        }

        auth()->user()->bookmarks()->attach($this->torrent->id);
        $this->isBookmarked = true;
        $this->dispatchBrowserEvent('success', ['type' => 'success',  'message' => 'Torrent Has Been Bookmarked Successfully!']);
    }

    final public function destroy(): void
    {
        auth()->user()->bookmarks()->detach($this->torrent->id);
        $this->isBookmarked = false;
        $this->dispatchBrowserEvent('success', ['type' => 'success',  'message' => 'Torrent Has Been Unbookmarked Successfully!']);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.small-bookmark-button');
    }
}

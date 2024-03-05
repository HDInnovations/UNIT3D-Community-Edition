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

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Livewire\Component;

class LikeButton extends Component
{
    public Post $post;

    public ?User $user = null;

    public int $likesCount;

    final public function mount(Post $post, int $likesCount): void
    {
        $this->user = auth()->user();
        $this->post = $post;
        $this->likesCount = $likesCount;
    }

    final public function store(): void
    {
        if ($this->user->id === $this->post->user_id) {
            $this->dispatch('error', type: 'error', message: 'You Cannot Like Your Own Post!');

            return;
        }

        $exist = Like::where('user_id', '=', $this->user->id)->where('post_id', '=', $this->post->id)->first();

        if ($exist) {
            $this->dispatch('error', type: 'error', message: 'You Have Already Liked Or Disliked This Post!');

            return;
        }

        $new = new Like();
        $new->user_id = $this->user->id;
        $new->post_id = $this->post->id;
        $new->like = true;
        $new->save();

        $this->likesCount += 1;

        $this->dispatch('success', type: 'success', message: 'Your Like Was Successfully Applied!');
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.like-button');
    }
}

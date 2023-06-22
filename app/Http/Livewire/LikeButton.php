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
use Livewire\Component;

class LikeButton extends Component
{
    public Post $post;

    public int $likesCount;

    final public function mount(Post $post, int $likesCount): void
    {
        $this->post = $post;
        $this->likesCount = $likesCount;
    }

    final public function store(): void
    {
        if (auth()->user()->id === $this->post->user_id) {
            $this->dispatchBrowserEvent('error', ['type' => 'error',  'message' => __('You Cannot Like Your Own Post!')]);

            return;
        }

        $exist = Like::where('user_id', '=', auth()->user()->id)->where('post_id', '=', $this->post->id)->first();
        if ($exist) {
            $this->dispatchBrowserEvent('error', ['type' => 'error',  'message' => __('You Have Already Liked Or Disliked This Post!')]);

            return;
        }

        $new = new Like();
        $new->user_id = auth()->user()->id;
        $new->post_id = $this->post->id;
        $new->like = 1;
        $new->save();

        $this->likesCount += 1;

        $this->dispatchBrowserEvent('success', ['type' => 'success',  'message' => __('Your Like Was Successfully Applied!')]);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.like-button');
    }
}

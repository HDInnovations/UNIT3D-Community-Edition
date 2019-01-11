<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\Like;
use App\Post;
use Brian2694\Toastr\Toastr;

class LikeController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * LikeController Constructor.
     *
     * @param Toastr               $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Like A Post.
     *
     * @param $postId
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function store($postId)
    {
        $post = Post::findOrFail($postId);
        $postUrl = "forums/topic/{$post->topic->slug}.{$post->topic->id}?page={$post->getPageNumber()}#post-{$postId}";

        $user = auth()->user();
        $like = $user->likes()->where('post_id', '=', $post->id)->where('like', '=', 1)->first();
        $dislike = $user->likes()->where('post_id', '=', $post->id)->where('dislike', '=', 1)->first();

        if ($like || $dislike) {
            return redirect($postUrl)
                ->with($this->toastr->error('You have already liked/disliked this post!', 'Bro', ['options']));
        } elseif ($user->id == $post->user_id) {
            return redirect($postUrl)
                ->with($this->toastr->error('You cannot like your own post!', 'Umm', ['options']));
        } else {
            $new = new Like();
            $new->user_id = $user->id;
            $new->post_id = $post->id;
            $new->like = 1;
            $new->save();

            return redirect($postUrl)
                ->with($this->toastr->success('Like Successfully Applied!', 'Yay', ['options']));
        }
    }

    /**
     * Dislike A Post.
     *
     * @param $postId
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function destroy($postId)
    {
        $post = Post::findOrFail($postId);
        $postUrl = "forums/topic/{$post->topic->slug}.{$post->topic->id}?page={$post->getPageNumber()}#post-{$postId}";

        $user = auth()->user();
        $like = $user->likes()->where('post_id', '=', $post->id)->where('like', '=', 1)->first();
        $dislike = $user->likes()->where('post_id', '=', $post->id)->where('dislike', '=', 1)->first();

        if ($like || $dislike) {
            return redirect($postUrl)
                ->with($this->toastr->error('You have already liked/disliked this post!', 'Bro', ['options']));
        } elseif ($user->id == $post->user_id) {
            return redirect($postUrl)
                ->with($this->toastr->error('You cannot dislike your own post!', 'Umm', ['options']));
        } else {
            $new = new Like();
            $new->user_id = $user->id;
            $new->post_id = $post->id;
            $new->dislike = 1;
            $new->save();

            return redirect($postUrl)
                ->with($this->toastr->success('Dislike Successfully Applied!', 'Yay', ['options']));
        }
    }
}

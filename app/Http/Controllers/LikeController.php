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

use App\Models\Like;
use App\Models\Post;

class LikeController extends Controller
{
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
                ->withErrors('You have already liked/disliked this post!');
        } elseif ($user->id == $post->user_id) {
            return redirect($postUrl)
                ->withErrors('You cannot like your own post!');
        } else {
            $new = new Like();
            $new->user_id = $user->id;
            $new->post_id = $post->id;
            $new->like = 1;
            $new->save();

            return redirect($postUrl)
                ->withSuccess('Like Successfully Applied!');
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
                ->withErrors('You have already liked/disliked this post!');
        } elseif ($user->id == $post->user_id) {
            return redirect($postUrl)
                ->withErrors('You cannot dislike your own post!');
        } else {
            $new = new Like();
            $new->user_id = $user->id;
            $new->post_id = $post->id;
            $new->dislike = 1;
            $new->save();

            return redirect($postUrl)
                ->withSuccess('Dislike Successfully Applied!');
        }
    }
}

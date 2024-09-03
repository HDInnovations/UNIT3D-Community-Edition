<?php

declare(strict_types=1);

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

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Like;

class LikeController extends Controller
{
    public function store(int $postId)
    {
        $user = auth()->user();
        $post = Post::withCount('likes')->findOrFail($postId);

        if ($user->id === $post->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot like your own post!',
            ], 400);
        }

        $exist = Like::where('user_id', $user->id)->where('post_id', $postId)->first();

        if ($exist) {
            return response()->json([
                'success' => false,
                'message' => 'You have already liked or disliked this post!',
            ], 400);
        }

        $like = new Like();
        $like->user_id = $user->id;
        $like->post_id = $postId;
        $like->like = true;
        $like->save();

        $post->loadCount('likes');

        return response()->json([
            'success'    => true,
            'likesCount' => $post->likes_count,
        ]);
    }
}

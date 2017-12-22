<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\Article;

class ArticleController extends Controller
{

    /**
     * Show Articles
     *
     * @access public
     * @return post.articles
     */
    public function articles()
    {
        // Fetch posts by created_at DESC order
        $articles = Article::orderBy('created_at', 'DESC')->paginate(6);

        return view('article.articles', ['articles' => $articles]);
    }

    /**
     * Show Article
     *
     * @access public
     * @return post.post
     */
    public function post($slug, $id)
    {
        // Find de right post
        $article = Article::findOrFail($id);
        // Get comments on this post
        $comments = $article->comments()->orderBy('created_at', 'DESC')->get();

        return view('article.article', ['article' => $article, 'comments' => $comments]);
    }

}

<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\Article;

class ArticleController extends Controller
{
    /**
     * Show Articles
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function articles()
    {
        $articles = Article::latest()->paginate(6);

        return view('article.articles', ['articles' => $articles]);
    }

    /**
     * Show Article
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function post($slug, $id)
    {
        $article = Article::findOrFail($id);
        $comments = $article->comments()->latest()->get();

        return view('article.article', ['article' => $article, 'comments' => $comments]);
    }
}

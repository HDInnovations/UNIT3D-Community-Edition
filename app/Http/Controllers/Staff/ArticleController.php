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

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreArticleRequest;
use App\Http\Requests\Staff\UpdateArticleRequest;
use App\Models\Article;
use Intervention\Image\Facades\Image;
use Exception;

/**
 * @see \Tests\Feature\Http\Controllers\ArticleControllerTest
 */
class ArticleController extends Controller
{
    /**
     * Display All Articles.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.article.index', [
            'articles' => Article::latest()
                ->with('user:id,username')
                ->withCount('comments')
                ->paginate(25),
        ]);
    }

    /**
     * Article Add Form.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.article.create');
    }

    /**
     * Store A New Article.
     */
    public function store(StoreArticleRequest $request): \Illuminate\Http\RedirectResponse
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            abort_if(\is_array($image), 400);

            $filename = 'article-'.uniqid('', true).'.'.$image->getClientOriginalExtension();
            $path = public_path('/files/img/'.$filename);
            Image::make($image->getRealPath())->fit(75, 75)->encode('png', 100)->save($path);
        }

        Article::create(['user_id' => $request->user()->id, 'image' => $filename ?? null] + $request->validated());

        return to_route('staff.articles.index')
            ->withSuccess('Your article has successfully published!');
    }

    /**
     * Article Edit Form.
     */
    public function edit(Article $article): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.article.edit', [
            'article' => $article,
        ]);
    }

    /**
     * Edit A Article.
     */
    public function update(UpdateArticleRequest $request, Article $article): \Illuminate\Http\RedirectResponse
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            abort_if(\is_array($image), 400);

            $filename = 'article-'.uniqid('', true).'.'.$image->getClientOriginalExtension();
            $path = public_path('/files/img/'.$filename);
            Image::make($image->getRealPath())->fit(75, 75)->encode('png', 100)->save($path);
        }

        $article->update(['image' => $filename ?? null,] + $request->validated());

        return to_route('staff.articles.index')
            ->withSuccess('Your article changes have successfully published!');
    }

    /**
     * Delete A Article.
     *
     * @throws Exception
     */
    public function destroy(Article $article): \Illuminate\Http\RedirectResponse
    {
        $article->comments()->delete();
        $article->delete();

        return to_route('staff.articles.index')
            ->withSuccess('Article has successfully been deleted');
    }
}

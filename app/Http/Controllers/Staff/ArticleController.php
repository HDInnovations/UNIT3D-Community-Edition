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

namespace App\Http\Controllers\Staff;

use Image;
use App\Article;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * ArticleController Constructor.
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Get All Articles.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $articles = Article::latest()->paginate(25);

        return view('Staff.article.index', ['articles' => $articles]);
    }

    /**
     * Article Add Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addForm()
    {
        return view('Staff.article.add');
    }

    /**
     * Add A Article.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        $article = new Article();
        $article->title = $request->input('title');
        $article->slug = str_slug($article->title);
        $article->content = $request->input('content');
        $article->user_id = auth()->user()->id;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'article-'.uniqid().'.'.$image->getClientOriginalExtension();
            $path = public_path('/files/img/'.$filename);
            Image::make($image->getRealPath())->fit(75, 75)->encode('png', 100)->save($path);
            $article->image = $filename;
        } else {
            // Use Default /public/img/missing-image.jpg
            $article->image = null;
        }

        $v = validator($article->toArray(), [
            'title'   => 'required',
            'slug'    => 'required',
            'content' => 'required|min:100',
            'user_id' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff_article_index')
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $article->save();

            return redirect()->route('staff_article_index')
                ->with($this->toastr->success('Your article has successfully published!', 'Yay!', ['options']));
        }
    }

    /**
     * Article Edit Form.
     *
     * @param $slug
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editForm($slug, $id)
    {
        $article = Article::findOrFail($id);

        return view('Staff.article.edit', ['article' => $article]);
    }

    /**
     * Edit A Article.
     *
     * @param \Illuminate\Http\Request $request
     * @param $slug
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $slug, $id)
    {
        $article = Article::findOrFail($id);
        $article->title = $request->input('title');
        $article->slug = str_slug($article->title);
        $article->content = $request->input('content');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'article-'.uniqid().'.'.$image->getClientOriginalExtension();
            $path = public_path('/files/img/'.$filename);
            Image::make($image->getRealPath())->fit(75, 75)->encode('png', 100)->save($path);
            $article->image = $filename;
        } else {
            // Use Default /public/img/missing-image.jpg
            $article->image = null;
        }

        $v = validator($article->toArray(), [
            'title'   => 'required',
            'slug'    => 'required',
            'content' => 'required|min:100',
        ]);

        if ($v->fails()) {
            return redirect()->route('staff_article_index')
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $article->save();

            return redirect()->route('staff_article_index')
                ->with($this->toastr->success('Your article changes have successfully published!', 'Yay!', ['options']));
        }
    }

    /**
     * Delete A Article.
     *
     * @param $slug
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function delete($slug, $id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return redirect()->route('staff_article_index')
            ->with($this->toastr->success('Article has successfully been deleted', 'Yay!', ['options']));
    }
}

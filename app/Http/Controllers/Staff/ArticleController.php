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

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Article;
use \Toastr;

class ArticleController extends Controller
{
    /**
     * Get All Articles
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $articles = Article::latest()->paginate(25);

        return view('Staff.article.index', ['articles' => $articles]);
    }

    /**
     * Article Add Form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addForm()
    {
        return view('Staff.article.add');
    }

    /**
     * Add A Article
     *
     * @param Request $request
     * @return Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        $article = new Article();
        $article->title = $request->input('title');
        $article->slug = str_slug($article->title);
        $article->content = $request->input('content');
        $article->user_id = auth()->user()->id;

        if ($request->hasFile('image') && $request->file('image')->getError() == 0) {
            $image = $request->file('image');
            if (in_array($image->getClientOriginalExtension(), ['jpg', 'JPG', 'jpeg', 'bmp', 'png', 'PNG', 'tiff', 'gif']) && preg_match('#image/*#', $image->getMimeType())) {
                    $filename = 'article-' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = public_path('/files/img/' . $filename);
                    Image::make($image->getRealPath())->fit(100, 100)->encode('png', 100)->save($path);
            } else {
                // Image null or wrong format
                $article->image = null;
            }
        } else {
            // Error on the image so null
            $article->image = null;
        }

        $v = validator($article->toArray(), [
            'title' => 'required',
            'slug' => 'required',
            'content' => 'required|min:100',
            'user_id' => 'required'
        ]);

        if ($v->fails()) {
            // Delete the image because the validation failed
            if (file_exists($request->file('image')->move(getcwd() . '/files/img/' . $article->image))) {
                unlink($request->file('image')->move(getcwd() . '/files/img/' . $article->image));
            }
            return redirect()->route('staff_article_index')
                ->with(Toastr::error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            auth()->user()->articles()->save($article);
            return redirect()->route('staff_article_index')
                ->with(Toastr::success('Your article has successfully published!', 'Yay!', ['options']));
        }
    }

    /**
     * Article Edit Form
     *
     * @param $slug
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editForm($slug, $id)
    {
        $article = Article::findOrFail($id);

        return view('Staff.article.edit', ['article' => $article]);
    }

    /**
     * Edit A Article
     *
     * @param Request $request
     * @param $slug
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $slug, $id)
    {
        $article = Article::findOrFail($id);
        $input = $request->all();
        $article->title = $input['title'];
        $article->slug = str_slug($article->title);
        $article->content = $input['content'];
        $article->user_id = auth()->user()->id;

        // Verify that an image was upload
        if ($request->hasFile('image') && $request->file('image')->getError() == 0) {
            // The file is an image
            if (in_array($request->file('image')->getClientOriginalExtension(), ['jpg', 'jpeg', 'bmp', 'png', 'tiff'])) {
                // Move and add the name to the object that will be saved
                $article->image = 'article-' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(getcwd() . '/files/img/', $article->image);
            } else {
                // Image null or wrong format
                $article->image = null;
            }
        } else {
            // Error on the image so null
            $article->image = null;
        }

        $v = validator($article->toArray(), [
            'title' => 'required',
            'slug' => 'required',
            'content' => 'required|min:100',
            'user_id' => 'required'
        ]);

        if ($v->fails()) {
            return redirect()->route('staff_article_index')
                ->with(Toastr::error('Your article changes have failed to publish!', 'Whoops!', ['options']));
        } else {
            $article->save();
            return redirect()->route('staff_article_index')
                ->with(Toastr::success('Your article changes have successfully published!', 'Yay!', ['options']));
        }
    }

    /**
     * Delete A Article
     *
     * @param $slug
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function delete($slug, $id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return redirect()->route('staff_article_index')
            ->with(Toastr::success('Article has successfully been deleted', 'Yay!', ['options']));
    }
}

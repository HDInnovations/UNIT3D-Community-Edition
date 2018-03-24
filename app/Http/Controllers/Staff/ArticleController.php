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
     * Show The Current Articles
     *
     * @access public
     * @return Staff.article.index
     */
    public function index()
    {
        $posts = Article::latest()->paginate(25);
        return view('Staff.article.index', ['posts' => $posts]);
    }

    /**
     * Add A Article
     *
     * @access public
     * @return Staff.article.add
     */
    public function add(Request $request)
    {
        if ($request->isMethod('POST')) {
            $input = $request->all();
            $post = new Article();
            $post->title = $input['title'];
            $post->slug = str_slug($post->title);
            $post->content = $input['content'];
            $post->user_id = auth()->user()->id;
            // Verify that an image was upload
            if ($request->hasFile('image') && $request->file('image')->getError() == 0) {
                // The file is an image
                if (in_array($request->file('image')->getClientOriginalExtension(), ['jpg', 'jpeg', 'bmp', 'png', 'tiff'])) {
                    // Move and add the name to the object that will be saved
                    $post->image = 'article-' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
                    $request->file('image')->move(getcwd() . '/files/img/', $post->image);
                } else {
                    // Image null or wrong format
                    $post->image = null;
                }
            } else {
                // Error on the image so null
                $post->image = null;
            }

            $v = validator($post->toArray(), $post->rules);
            if ($v->fails()) {
                // Delete the image because the validation failed
                if (file_exists($request->file('image')->move(getcwd() . '/files/img/' . $post->image))) {
                    unlink($request->file('image')->move(getcwd() . '/files/img/' . $post->image));
                }
                return redirect()->route('staff_article_index')->with(Toastr::error('Your article has failed to published!', 'Whoops!', ['options']));
            } else {
                auth()->user()->articles()->save($post);
                return redirect()->route('staff_article_index')->with(Toastr::success('Your article has successfully published!', 'Yay!', ['options']));
            }
        }
        return view('Staff.article.add');
    }

    /**
     * Edit Article
     *
     * @access public
     * @param $slug Slug of article
     * @param $id Id of article
     * @return Staff.article.edit
     */
    public function edit(Request $request, $slug, $id)
    {
        $post = Article::findOrFail($id);
        if ($request->isMethod('POST')) {
            $input = $request->all();
            $post->title = $input['title'];
            $post->slug = str_slug($post->title);
            $post->content = $input['content'];
            $post->user_id = auth()->user()->id;

            // Verify that an image was upload
            if ($request->hasFile('image') && $request->file('image')->getError() == 0) {
                // The file is an image
                if (in_array($request->file('image')->getClientOriginalExtension(), ['jpg', 'jpeg', 'bmp', 'png', 'tiff'])) {
                    // Move and add the name to the object that will be saved
                    $post->image = 'article-' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
                    $request->file('image')->move(getcwd() . '/files/img/', $post->image);
                } else {
                    // Image null or wrong format
                    $post->image = null;
                }
            } else {
                // Error on the image so null
                $post->image = null;
            }

            $v = validator($post->toArray(), $post->rules);
            if ($v->fails()) {
                return redirect()->route('staff_article_index')->with(Toastr::error('Your article changes have failed to publish!', 'Whoops!', ['options']));
            } else {
                $post->save();
                return redirect()->route('staff_article_index')->with(Toastr::success('Your article changes have successfully published!', 'Yay!', ['options']));
            }
        }
        return view('Staff.article.edit', ['post' => $post]);
    }

    /**
     * Delete the article
     *
     * @access public
     * @param $slug Slug of article
     * @param $id Id of article
     * @return void
     */
    public function delete($slug, $id)
    {
        $post = Article::findOrFail($id);
        $post->delete();
        return redirect()->route('staff_article_index')->with(Toastr::success('Article has successfully been deleted', 'Yay!', ['options']));
    }
}

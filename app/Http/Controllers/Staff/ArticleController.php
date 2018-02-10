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

namespace App\Http\Controllers\Staff;

use App\Article;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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
        $posts = Article::orderBy('created_at', 'DESC')->paginate(20);
        return view('Staff.article.index', ['posts' => $posts]);
    }

    /**
     * Add A Article
     *
     * @access public
     * @return Staff.article.add
     */
    public function add()
    {
        if (Request::isMethod('post')) {
            $input = Request::all();
            $post = new Article();
            $post->title = $input['title'];
            $post->slug = str_slug($post->title);
            $post->content = $input['content'];
            $post->user_id = Auth::user()->id;
            // Verify that an image was upload
            if (Request::hasFile('image') && Request::file('image')->getError() == 0) {
                // The file is an image
                if (in_array(Request::file('image')->getClientOriginalExtension(), ['jpg', 'jpeg', 'bmp', 'png', 'tiff'])) {
                    // Move and add the name to the object that will be saved
                    $post->image = 'article-' . uniqid() . '.' . Request::file('image')->getClientOriginalExtension();
                    Request::file('image')->move(getcwd() . '/files/img/', $post->image);
                } else {
                    // Image null or wrong format
                    $post->image = null;
                }
            } else {
                // Error on the image so null
                $post->image = null;
            }

            $v = Validator::make($post->toArray(), $post->rules);
            if ($v->fails()) {
                // Delete the image because the validation failed
                if (file_exists(Request::file('image')->move(getcwd() . '/files/img/' . $post->image))) {
                    unlink(Request::file('image')->move(getcwd() . '/files/img/' . $post->image));
                }
                return back()->with(Toastr::error('Validation Checks Have Failed', 'Error', ['options']));
            } else {
                Auth::user()->articles()->save($post);
                return Redirect::route('staff_article_index')->with('message', 'Your article has been published');
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
    public function edit($slug, $id)
    {
        $post = Article::findOrFail($id);
        if (Request::isMethod('post')) {
            $input = Request::all();
            $post->title = $input['title'];
            $post->slug = str_slug($post->title);
            $post->content = $input['content'];
            $post->user_id = Auth::user()->id;

            // Verify that an image was upload
            if (Request::hasFile('image') && Request::file('image')->getError() == 0) {
                // The file is an image
                if (in_array(Request::file('image')->getClientOriginalExtension(), ['jpg', 'jpeg', 'bmp', 'png', 'tiff'])) {
                    // Move and add the name to the object that will be saved
                    $post->image = 'article-' . uniqid() . '.' . Request::file('image')->getClientOriginalExtension();
                    Request::file('image')->move(getcwd() . '/files/img/', $post->image);
                } else {
                    // Image null or wrong format
                    $post->image = null;
                }
            } else {
                // Error on the image so null
                $post->image = null;
            }

            $v = Validator::make($post->toArray(), $post->rules);
            if ($v->fails()) {
                Session::put('message', 'An error has occured');
            } else {
                $post->save();
                return Redirect::route('staff_article_index')->with('message', 'Your article has been modified');
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
        return Redirect::route('staff_article_index')->with('message', 'This article has been deleted');
    }
}

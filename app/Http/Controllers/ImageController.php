<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Image;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

final class ImageController extends Controller
{
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private $viewFactory;
    /**
     * @var \Illuminate\Routing\Redirector
     */
    private $redirector;
    /**
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    private $responseFactory;

    public function __construct(Factory $viewFactory, Redirector $redirector, ResponseFactory $responseFactory)
    {
        $this->viewFactory = $viewFactory;
        $this->redirector = $redirector;
        $this->responseFactory = $responseFactory;
    }

    /**
     * Show Image Create Form.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id): Factory
    {
        $album = Album::find($id);

        return $this->viewFactory->make('album.image', ['album' => $album]);
    }

    /**
     * Store A New Image.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function store(Request $request)
    {
        $image = new Image();
        $image->user_id = $request->user()->id;
        $image->album_id = $request->input('album_id');
        $image->description = $request->input('description');
        $image->type = $request->input('type');

        $file = $request->file('image');
        $random_name = uniqid();
        $destinationPath = public_path('/files/img/');
        $extension = $file->getClientOriginalExtension();
        $filename = 'album-image_'.$random_name.'.'.$extension;
        $uploadSuccess = $request->file('image')->move($destinationPath, $filename);
        $image->image = $filename;

        $v = validator($image->toArray(), [
            'album_id'    => 'required|numeric|exists:albums,id',
            'user_id'     => 'required',
            'description' => 'required',
            'type'        => 'required',
            'image'       => 'required',
        ]);

        if ($v->fails()) {
            return $this->redirector->route('images.create', ['id' => $request->input('album_id')])
                ->withErrors($v->errors());
        } else {
            $image->save();

            return $this->redirector->route('albums.show', ['id' => $request->input('album_id')])
                ->withSuccess('Your image has successfully published!');
        }
    }

    /**
     * Download A Image.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function download($id): RedirectResponse
    {
        $image = Image::findOrFail($id);
        $filename = $image->image;

        if (! file_exists(getcwd().'/files/img/'.$filename)) {
            return $this->redirector->route('show_album', ['id' => $image->album_id])
                ->withErrors('Image File Not Found! Please Report This To Staff!');
        }

        $image->downloads++;
        $image->save();

        return $this->responseFactory->download(getcwd().'/files/img/'.$filename);
    }

    /**
     * Delete A Image.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param                            $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        $user = $request->user();
        $image = Image::findOrFail($id);

        abort_unless($user->group->is_modo || $user->id === $image->user_id, 403);
        $image->delete();

        return $this->redirector->route('albums.show', ['id' => $image->album_id])
            ->withSuccess('Image has successfully been deleted');
    }
}

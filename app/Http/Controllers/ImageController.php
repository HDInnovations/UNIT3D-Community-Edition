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

use App\Models\Album;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Image Add Form.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addForm($id)
    {
        $album = Album::find($id);

        return view('gallery.addimage', ['album' => $album]);
    }

    /**
     * Add A Image To A Album.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        $image = new Image();
        $image->user_id = auth()->user()->id;
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
            return redirect()->route('add_image', ['id' => $request->input('album_id')])
                ->withErrors($v->errors());
        } else {
            $image->save();

            return redirect()->route('show_album', ['id' => $request->input('album_id')])
                ->withSuccess('Your image has successfully published!');
        }
    }

    /**
     * Move A Image.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function move(Request $request)
    {
        $user = auth()->user();

        abort_unless($user->group->is_modo, 403);
        $image = Image::findOrFail($request->input('photo'));
        $image->album_id = $request->input('new_album');

        $v = validator($image->toArray(), [
            'new_album' => 'required|numeric|exists:albums,id',
            'image'     => 'required|numeric|exists:images,id',
        ]);

        if ($v->fails()) {
            return redirect()->route('gallery')
                ->withErrors($v->errors());
        } else {
            $image->save();

            return redirect()->route('show_album', ['id' => $request->input('new_album')]);
        }
    }

    /**
     * Download A Image.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function download($id)
    {
        $image = Image::findOrFail($id);
        $filename = $image->image;

        if (! file_exists(getcwd().'/files/img/'.$filename)) {
            return redirect()->route('show_album', ['id' => $image->album_id])
                ->withErrors('Image File Not Found! Please Report This To Staff!');
        }

        $image->downloads++;
        $image->save();

        return response()->download(getcwd().'/files/img/'.$filename);
    }

    /**
     * Delete A Image.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $image = Image::findOrFail($id);

        abort_unless($user->group->is_modo || $user->id === $image->user_id, 403);
        $image->delete();

        return redirect()->route('show_album', ['id' => $image->album_id])
            ->withSuccess('Image has successfully been deleted');
    }
}

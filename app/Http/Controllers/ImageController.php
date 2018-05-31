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

use Illuminate\Http\Request;
use App\Album;
use App\Image;
use \Toastr;

class ImageController extends Controller
{
    /**
     * Image Add Form
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addForm($id)
    {
        $album = Album::find($id);

        return view('gallery.addimage', ['album' => $album]);
    }

    /**
     * Add A Image To A Album
     *
     * @param Request $request
     * @return Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        $image = new Image();
        $image->user_id = auth()->user()->id;
        $image->album_id = $request->input('album_id');
        $image->description = $request->input('description');
        $image->type = $request->input('type');

        if ($request->hasFile('image') && $request->file('image')->getError() == 0) {
            $image = $request->file('image');
            $filename = 'album-image_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/files/img/'), $filename);
            $image->image = $filename;
        } else {
            // Image null or faulty
            return redirect()->route('add_image', ['id' => $request->input('album_id')])
                ->withInput()
                ->with(Toastr::error('Bad Image Upload!', 'Whoops!', ['options']));
        }

        $v = validator($image->toArray(), [
            'album_id' => 'required|numeric|exists:albums,id',
            'user_id' => 'required',
            'description' => 'required',
            'type' => 'required',
            'image' => 'required|image'
        ]);

        if ($v->fails()) {
            // Delete the cover_image because the validation failed
            if (file_exists($request->file('image')->move(getcwd() . '/files/img/' . $image->image))) {
                unlink($request->file('image')->move(getcwd() . '/files/img/' . $image->image));
            }

            return redirect()->route('add_image', ['id' => $request->input('album_id')])
                ->withErrors($v)
                ->withInput()
                ->with(Toastr::error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $image->save();

            return redirect()->route('show_album',['id'=>$request->input('album_id')])
                ->with(Toastr::success('Your image has successfully published!', 'Yay!', ['options']));
        }
    }

    /**
     * Delete A Image
     *
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $image = Image::findOrFail($id);
        $image->delete();

        return redirect()->route('show_album', ['id' => $image->album_id])
            ->with(Toastr::success('Image has successfully been deleted', 'Yay!', ['options']));
    }
}
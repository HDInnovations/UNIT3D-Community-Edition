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

namespace App\Http\Controllers\Staff;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;

final class NoteController extends Controller
{
    /**
     * Display All User Notes.
     *
     * @return Factory|View
     */
    public function index()
    {
        $notes = Note::latest()->paginate(25);

        return view('Staff.note.index', ['notes' => $notes]);
    }

    /**
     * Store A New User Note.
     *
     * @param Request $request
     * @param $username
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function store(Request $request, $username)
    {
        $staff = $request->user();
        $user = User::where('username', '=', $username)->firstOrFail();

        $note = new Note();
        $note->user_id = $user->id;
        $note->staff_id = $staff->id;
        $note->message = $request->input('message');

        $v = validator($note->toArray(), [
            'user_id'  => 'required',
            'staff_id' => 'required',
            'message'  => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('users.show', ['username' => $user->username])
                ->withErrors($v->errors());
        }
        $note->save();
        return redirect()->route('users.show', ['username' => $user->username])
            ->withSuccess('Note Has Successfully Posted');
    }

    /**
     * Delete A User Note.
     *
     * @param $id
     *
     * @return RedirectResponse
     */
    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        $note = Note::findOrFail($id);
        $user = User::findOrFail($note->user_id);
        $note->delete();

        return redirect()->route('users.show', ['username' => $user->username])
            ->withSuccess('Note Has Successfully Been Deleted');
    }
}

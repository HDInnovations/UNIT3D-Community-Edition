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
use App\Note;
use App\User;
use \Toastr;

class NoteController extends Controller
{
    /**
     * User Staff Notes System
     *
     *
     */

    public function getNotes()
    {
        $notes = Note::latest()->paginate(25);

        return view('Staff.notes.index', ['notes' => $notes]);
    }

    public function postNote(Request $request, $username, $id)
    {
        $staff = auth()->user();
        $user = User::findOrFail($id);

        $v = validator($request->all(), [
            'user_id' => 'required',
            'staff_id' => 'required|numeric',
            'message' => 'required',
        ]);

        $note = new Note();
        $note->user_id = $user->id;
        $note->staff_id = $staff->id;
        $note->message = $request->input('message');
        $note->save();

        // Activity Log
        \LogActivity::addToLog("Staff Member {$staff->username} has added a note on {$user->username} account.");

        return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])->with(Toastr::success('Your Staff Note Has Successfully Posted', 'Yay!', ['options']));
    }

}

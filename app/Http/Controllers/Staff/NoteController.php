<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     BluCrew
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
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
    public function postNote($username, $id)
    {
        $staff = Auth::user();
        $user = User::findOrFail($id);

        $v = Validator::make(Request::all(), [
            'user_id' => 'required',
            'staff_id' => 'required|numeric',
            'message' => 'required',
        ]);

        $note = new Note();
        $note->user_id = $user->id;
        $note->staff_id = $staff->id;
        $note->message = Request::get('message');
        $note->save();

        // Activity Log
        \LogActivity::addToLog("Staff Member " . $staff->username . " has added a note on " . $user->username . " account.");

        return Redirect::route('profil', ['username' => $user->username, 'id' => $user->id])->with(Toastr::success('Your Staff Note Has Successfully Posted', 'Success!', ['options']));
    }

    public function getNotes()
    {
        $notes = Note::orderBy('created_at', 'DESC')->get();

        return view('Staff.notes.index', ['notes' => $notes]);
    }
}

<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreNoteRequest;
use App\Models\Note;
use App\Models\User;

/**
 * @see \Tests\Feature\Http\Controllers\Staff\NoteControllerTest
 */
class NoteController extends Controller
{
    /**
     * Display All User Notes.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('Staff.note.index');
    }

    /**
     * Store A New User Note.
     */
    public function store(StoreNoteRequest $request, string $username): \Illuminate\Http\RedirectResponse
    {
        Note::create([
            'user_id'  => User::where('username', '=', $username)->firstOrFail()->id,
            'staff_id' => $request->user()->id,
            'message'  => $request->message,
        ]);

        return \to_route('users.show', ['username' => $username])
            ->withSuccess('Note Has Successfully Posted');
    }

    /**
     * Delete A User Note.
     *
     * @throws \Exception
     */
    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        $note = Note::findOrFail($id);
        $user = User::findOrFail($note->user_id);
        $note->delete();

        return \to_route('users.show', ['username' => $user->username])
            ->withSuccess('Note Has Successfully Been Deleted');
    }
}

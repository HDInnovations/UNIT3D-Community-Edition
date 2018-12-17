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

use App\User;
use App\Mail\Contact;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * ContactController Constructor.
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Contact Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('contact.index');
    }

    /**
     * Send A Contact Email To Owner/First User.
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function contact(Request $request)
    {
        // Fetch owner account
        $user = User::where('id', '=', 3)->first();

        $input = $request->all();
        Mail::to($user->email, $user->username)->send(new Contact($input));

        return redirect()->route('home')
            ->with($this->toastr->success('Your Message Was Successfully Sent', 'Yay!', ['options']));
    }
}

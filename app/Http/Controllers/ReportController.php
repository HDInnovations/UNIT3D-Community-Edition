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

namespace App\Http\Controllers;

use App\Report;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use \Toastr;

class ReportController extends Controller
{
    /**
     * Reports System
     *
     *
     */
    public function postReport()
    {
        $user = Auth::user();

        $v = Validator::make(Request::all(), [
            'type' => 'required',
            'reporter_id' => 'required|numeric',
            'title' => 'required',
            'message' => 'required',
            'solved' => 'required|numeric'
        ]);

        $report = new Report();
        $report->type = Request::get('type');
        $report->reporter_id = $user->id;
        $report->title = Request::get('title');
        $report->message = Request::get('message');
        $report->solved = 0;
        $report->save();

        return redirect()->route('home')->with(Toastr::success('Your report has been successfully sent', 'Success!', ['options']));
    }
}

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
use App\Donation;

class DonationController extends Controller
{
    /**
     * Reports System
     *
     *
     */
    public function getDonations()
    {
        $donations = Donation::orderBy('created_at', 'DESC')->get();

        return view('Staff.donation.index', ['donations' => $donations]);
    }
}

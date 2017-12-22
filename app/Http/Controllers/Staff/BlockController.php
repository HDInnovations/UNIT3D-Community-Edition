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

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

class BlockController extends Controller
{

    /**
     * Blocks Settings
     *
     *
     * @access public
     * @return Staff.blocks.index
     */
    public function index()
    {
        return view('Staff.blocks.index');
    }
}

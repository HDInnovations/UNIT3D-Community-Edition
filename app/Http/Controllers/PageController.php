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

use App\Page;

class PageController extends Controller
{

    /**
     * Displays the requested page
     *
     *
     */
    public function page($slug, $id)
    {
        $page = Page::findOrFail($id);

        return view('page.page', ['page' => $page]);
    }

    /**
     * Credits Page
     *
     *
     */
    public function credits()
    {
        return view('page.credits');
    }

    /**
     * About Us Page
     *
     *
     */
    public function about()
    {
        return view('page.aboutus');
    }

}

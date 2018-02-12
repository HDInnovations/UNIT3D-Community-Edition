<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Ryuu
 */

namespace App\Helpers;

use Decoda\Decoda;
use App\Hook\ClickableHook;
use Illuminate\Database\Eloquent\Model;

use Config;

class Bbcode
{
    private function __construct()
    {
    }

    public static function decodaWithDefaults($data)
    {
        $code = new Decoda($data);
        $code->defaults();
        $code->removeHook('Censor');
        $code->removeHook('Clickable');
        $code->addHook(new ClickableHook());
        $code->setXhtml(false);
        $code->setStrict(false);
        $code->setLineBreaks(true);
        return $code;
    }

    public static function parse($data)
    {
        return self::decodaWithDefaults($data)->parse();
    }
}

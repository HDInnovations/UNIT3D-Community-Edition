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

namespace App\Models;

use App\Helpers\Bbcode;
use App\Helpers\MarkdownExtra;
use Illuminate\Database\Eloquent\Model;

class Wiki extends Model
{
    protected $guarded = [];

    /**
     * Belongs To A Category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<WikiCategory, self>
     */
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(WikiCategory::class);
    }

    /**
     * Parse Content And Return Valid HTML.
     */
    public function getContentHtml(): string
    {
        return (new MarkdownExtra())->text((new Bbcode())->parse($this->content, false));
    }
}

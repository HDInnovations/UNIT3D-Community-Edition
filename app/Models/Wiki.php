<?php

declare(strict_types=1);

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
use App\Helpers\MarkdownHelper;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Wiki.
 *
 * @property int                             $id
 * @property string                          $name
 * @property string                          $content
 * @property int                             $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Wiki extends Model
{
    use Auditable;

    protected $guarded = [];

    /**
     * Belongs To A Category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<WikiCategory, $this>
     */
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(WikiCategory::class);
    }

    /**
     * Parse Content And Return Valid HTML.
     *
     * @throws \League\CommonMark\Exception\CommonMarkException
     */
    public function getContentHtml(): string
    {
        return new MarkdownHelper()->convertToHtml(htmlspecialchars_decode(new Bbcode()->parse($this->content, false)));
    }
}

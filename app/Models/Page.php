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

use App\Helpers\BBCodeConverter;
use App\Helpers\MarkdownExtra;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    use Auditable;

    /**
     * Set The Pages Content After Its Been Purified.
     */
    public function setContentAttribute(string $value): void
    {
        $this->attributes['content'] = $value;
    }

    /**
     * Parse Content And Return Valid HTML.
     */
    public function getContentHtml(): ?string
    {
        $content = (new BBCodeConverter($this->content))->toMarkdown();

        return (new MarkdownExtra())->text($content);
    }
}

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
use App\Helpers\Linkify;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use voku\helper\AntiXSS;

class Article extends Model
{
    use HasFactory;
    use Auditable;

    /**
     * Belongs To A User.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Has Many Comments.
     */
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Article Trimming.
     */
    public function getBrief(int $length = 20, bool $ellipses = true, bool $stripHtml = false): string
    {
        $input = $this->content;
        //strip tags, if desired
        if ($stripHtml) {
            $input = \strip_tags($input);
        }

        //no need to trim, already shorter than trim length
        if (\strlen($input) <= $length) {
            return $input;
        }

        //find last space within length
        $lastSpace = \strrpos(\substr($input, 0, $length), ' ');
        $trimmedText = \substr($input, 0, $lastSpace);

        //add ellipses (...)
        if ($ellipses) {
            $trimmedText .= '...';
        }

        return $trimmedText;
    }

    /**
     * Set The Articles Content After Its Been Purified.
     */
    public function setContentAttribute(string $value): void
    {
        $this->attributes['content'] = \htmlspecialchars((new AntiXSS())->xss_clean($value), ENT_NOQUOTES);
    }

    /**
     * Parse Content And Return Valid HTML.
     */
    public function getContentHtml(): string
    {
        $bbcode = new Bbcode();

        return (new Linkify())->linky($bbcode->parse($this->content, true));
    }
}

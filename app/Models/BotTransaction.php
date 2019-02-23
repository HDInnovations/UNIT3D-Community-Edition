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
 * @author     singularity43
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BotTransaction extends Model
{
    /**
     * The Database Table Used By The Model.
     *
     * @var string
     */
    protected $table = 'bot_transactions';

    /**
     * Indicates If The Model Should Be Timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    // Bad name to not conflict with sender (not sender_id)

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Belongs To A Bot.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    // Bad name to not conflict with sender (not sender_id)

    public function bot()
    {
        return $this->belongsTo(Bot::class)->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Get the Bot transaction type answer as string.
     *
     * @return int
     */
    public function forHumans()
    {
        if ($this->type == 'bon') {
            return 'BON';
        }

        return 'Unknown';
    }
}

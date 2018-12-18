<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App;

use Hootlex\Moderation\Moderatable;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use Moderatable;

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Application Has Been Moderated By.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function moderated()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    /**
     * A Application Has Many Image Proofs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function imageProofs()
    {
        return $this->hasMany(ApplicationImageProof::class);
    }

    /**
     * A Application Has Many URL Proofs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function urlProofs()
    {
        return $this->hasMany(ApplicationUrlProof::class);
    }
}

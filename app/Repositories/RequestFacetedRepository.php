<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Repositories;

use App\Models\Category;
use App\Models\Type;
use Illuminate\Support\Collection;
use Illuminate\Translation\Translator;

final class RequestFacetedRepository
{
    /**
     * @var \Illuminate\Translation\Translator
     */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Return a collection of Category Name from storage.
     *
     * @return \Illuminate\Support\Collection
     */
    public function categories(): Collection
    {
        return Category::all()->sortBy('position')->pluck('name', 'id');
    }

    /**
     * Return a collection of Type Name from storage.
     *
     * @return \Illuminate\Support\Collection
     */
    public function types(): Collection
    {
        return Type::all()->sortBy('position')->pluck('name', 'id');
    }

    /**
     * Options for sort the search result.
     *
     * @return array
     */
    public function sorting(): array
    {
        return [
            'created_at' => $this->translator->trans('torrent.date'),
            'name'       => $this->translator->trans('torrent.name'),
            'bounty'     => $this->translator->trans('request.bounty'),
            'votes'      => $this->translator->trans('request.votes'),
        ];
    }

    /**
     * Options for sort the search result by direction.
     *
     * @return array
     */
    public function direction(): array
    {
        return [
            'desc' => $this->translator->trans('common.descending'),
            'asc'  => $this->translator->trans('common.ascending'),
        ];
    }
}

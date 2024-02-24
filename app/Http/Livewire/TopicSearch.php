<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Livewire;

use App\Models\ForumCategory;
use App\Models\Topic;
use Livewire\Component;
use Livewire\WithPagination;

class TopicSearch extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortField = 'last_post_created_at';
    public string $sortDirection = 'desc';
    public string $label = '';
    public string $state = '';
    public string $subscribed = '';
    public string $forumId = '';
    public string $read = '';

    /**
     * @var array<mixed>
     */
    protected $queryString = [
        'search'        => ['except' => ''],
        'sortField'     => ['except' => 'last_post_created_at'],
        'sortDirection' => ['except' => 'desc'],
        'read'          => ['except' => ''],
        'label'         => ['except' => ''],
        'state'         => ['except' => ''],
        'subscribed'    => ['except' => ''],
        'forumId'       => ['except' => ''],
    ];

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, ForumCategory>
     */
    final public function getForumCategoriesProperty(): \Illuminate\Database\Eloquent\Collection
    {
        return ForumCategory::query()
            ->with(['forums' => fn ($query) => $query
                ->whereRelation('permissions', [['read_topic', '=', 1], ['group_id', '=', auth()->user()->group_id]])
            ])
            ->orderBy('position')
            ->get()
            ->filter(fn ($category) => $category->forums->isNotEmpty());
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Topic>
     */
    final public function getTopicsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Topic::query()
            ->select('topics.*')
            ->with([
                'user.group',
                'latestPoster',
                'forum',
                'reads' => fn ($query) => $query->whereBelongsto(auth()->user()),
            ])
            ->whereRelation('forumPermissions', [['read_topic', '=', 1], ['group_id', '=', auth()->user()->group_id]])
            ->when($this->search !== '', fn ($query) => $query->where('name', 'LIKE', '%'.$this->search.'%'))
            ->when($this->label !== '', fn ($query) => $query->where($this->label, '=', 1))
            ->when($this->state !== '', fn ($query) => $query->where('state', '=', $this->state))
            ->when(
                $this->subscribed === 'include',
                fn ($query) => $query
                    ->whereRelation('subscribedUsers', 'users.id', '=', auth()->id())
            )
            ->when(
                $this->subscribed === 'exclude',
                fn ($query) => $query
                    ->whereDoesntHave('subscribedUsers', fn ($query) => $query->where('users.id', '=', auth()->id()))
            )
            ->when(
                $this->read === 'some',
                fn ($query) => $query
                    ->whereHas(
                        'reads',
                        fn ($query) => $query
                            ->whereBelongsto(auth()->user())
                            ->whereColumn('last_post_id', '>', 'last_read_post_id')
                    )
            )
            ->when(
                $this->read === 'all',
                fn ($query) => $query
                    ->whereHas(
                        'reads',
                        fn ($query) => $query
                            ->whereBelongsto(auth()->user())
                            ->whereColumn('last_post_id', '=', 'last_read_post_id')
                    )
            )
            ->when(
                $this->read === 'none',
                fn ($query) => $query
                    ->whereDoesntHave('reads', fn ($query) => $query->whereBelongsTo(auth()->user()))
            )
            ->when($this->forumId !== '', fn ($query) => $query->where('forum_id', '=', $this->forumId))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(25);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.topic-search', [
            'topics'          => $this->topics,
            'forumCategories' => $this->forumCategories,
        ]);
    }
}

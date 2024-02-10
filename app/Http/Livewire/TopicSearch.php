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

use App\Models\Forum;
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
    public String $forumId = '';

    /**
     * @var array<mixed>
     */
    protected $queryString = [
        'search'        => ['except' => ''],
        'sortField'     => ['except' => 'last_post_created_at'],
        'sortDirection' => ['except' => 'desc'],
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
     * @return \Illuminate\Support\Collection<int, Forum>
     */
    final public function getForumCategoriesProperty(): \Illuminate\Support\Collection
    {
        return Forum::query()
            ->with(['forums' => fn ($query) => $query
                ->whereRelation('permissions', [['read_topic', '=', 1], ['group_id', '=', auth()->user()->group_id]])
            ])
            ->whereNull('parent_id')
            ->whereRelation('permissions', [['read_topic', '=', 1], ['group_id', '=', auth()->user()->group_id]])
            ->orderBy('position')
            ->get();
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Topic>
     */
    final public function getTopicsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Topic::query()
            ->select('topics.*')
            ->with('user', 'user.group', 'latestPoster', 'forum')
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
            ->when($this->forumId !== '', fn ($query) => $query->where(
                fn ($query) => $query
                    ->where('forum_id', '=', $this->forumId)
                    ->orWhereIn('forum_id', Forum::where('parent_id', '=', $this->forumId)->select('id'))
            ))
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

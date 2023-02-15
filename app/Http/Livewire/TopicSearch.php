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

    public String $search = '';
    public String $sortField = 'last_reply_at';
    public String $sortDirection = 'desc';
    public String $label = '';
    public String $state = '';
    public String $subscribed = '';
    public String $forumId = '';

    final public function updatingSearch(): void
    {
        $this->resetPage();
    }

    final public function getForumCategoriesProperty()
    {
        return Forum::query()
            ->with(['forums' => fn ($query) => $query
                ->whereRelation('permissions', [['show_forum', '=', 1], ['group_id', '=', auth()->user()->group->id]])
            ])
            ->where('parent_id', '=', 0)
            ->whereRelation('permissions', [['show_forum', '=', 1], ['group_id', '=', auth()->user()->group->id]])
            ->orderBy('position')
            ->get();
    }

    final public function getTopicsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Topic::query()
            ->select('topics.*')
            ->with('user', 'user.group')
            ->whereRelation('forumPermissions', [['show_forum', '=', 1], ['group_id', '=', auth()->user()->group->id]])
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
            ->orderByDesc('pinned')
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

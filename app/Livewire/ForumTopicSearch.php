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

namespace App\Livewire;

use App\Models\Forum;
use App\Models\Subscription;
use App\Models\Topic;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ForumTopicSearch extends Component
{
    use WithPagination;

    #[Url]
    public String $search = '';

    #[Url]
    public String $sortField = 'last_reply_at';

    #[Url]
    public String $sortDirection = 'desc';

    #[Url]
    public String $label = '';

    #[Url]
    public String $state = '';

    #[Url]
    public String $subscribed = '';

    public Forum $forum;

    public ?Subscription $subscription;

    final public function mount(Forum $forum): void
    {
        $this->forum = $forum;
        $this->subscription = Subscription::where('user_id', '=', auth()->id())->where('forum_id', '=', $forum->id)->first();
    }

    final public function updatedPage(): void
    {
        $this->dispatch('paginationChanged');
    }

    final public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Topic>
     */
    #[Computed]
    final public function topics(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Topic::query()
            ->select('topics.*')
            ->with('user', 'user.group', 'latestPoster', 'forum:id,name')
            ->where('topics.forum_id', '=', $this->forum->id)
            ->whereRelation('forumPermissions', [['show_forum', '=', 1], ['group_id', '=', auth()->user()->group_id]])
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
            ->orderByDesc('pinned')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(25);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.forum-topic-search', [
            'topics' => $this->topics,
        ]);
    }
}

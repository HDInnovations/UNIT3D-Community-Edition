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

use App\Models\Post;
use App\Models\Topic;
use Livewire\Component;
use Livewire\WithPagination;

class PostSearch extends Component
{
    use WithPagination;

    public String $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];
    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function updatingSearch(): void
    {
        $this->resetPage();
    }

    final public function getPostsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Post::query()
            ->with('user', 'user.group', 'topic:id,name')
            ->withCount('likes', 'dislikes', 'authorPosts', 'authorTopics')
            ->withSum('tips', 'cost')
            ->withExists([
                'likes'    => fn ($query) => $query->where('user_id', '=', auth()->id()),
                'dislikes' => fn ($query) => $query->where('user_id', '=', auth()->id()),
            ])
            ->whereNotIn(
                'topic_id',
                Topic::query()
                    ->whereRelation(
                        'forumPermissions',
                        fn ($query) => $query
                            ->where('group_id', '=', auth()->user()->group_id)
                            ->where(
                                fn ($query) => $query
                                    ->where('show_forum', '!=', 1)
                                    ->orWhere('read_topic', '!=', 1)
                            )
                    )
                    ->select('id')
            )
            ->when($this->search !== '', fn ($query) => $query->where('content', 'LIKE', '%'.$this->search.'%'))
            ->orderByDesc('created_at')
            ->paginate(25);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.post-search', [
            'posts' => $this->posts,
        ]);
    }
}

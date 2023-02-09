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
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TopicPostSearch extends Component
{
    use WithPagination;

    public String $search = '';

    public Topic $topic;

    final public function mount(Topic $topic): void
    {
        $this->topic = $topic;
    }

    final public function updatingSearch(): void
    {
        $this->resetPage();
    }

    final public function getPostsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Post::query()
            ->select('posts.*')
            ->with('user', 'user.group', 'user.topics', 'user.posts', 'topic', 'tips')
            ->withCount([
                'likes'                  => fn ($query) => $query->where('like', '=', 1),
                'likes as dislike_count' => fn ($query) => $query->where('dislike', '=', 1),
            ])
            ->where('topic_id', '=', $this->topic->id)
            ->join('topics', 'topics.id', '=', 'posts.topic_id')
            ->join(
                'permissions',
                fn ($query) => $query
                    ->on('permissions.forum_id', '=', 'topics.forum_id')
                    ->on('permissions.group_id', '=', DB::raw((int) auth()->user()->group->id))
                    ->on('permissions.show_forum', '=', DB::raw(1))
                    ->on('permissions.read_topic', '=', DB::raw(1))
            )
            ->when($this->search !== '', fn ($query) => $query->where('content', 'LIKE', '%'.$this->search.'%'))
            ->orderBy('created_at')
            ->paginate(25);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.topic-post-search', [
            'topic' => $this->topic,
            'posts' => $this->posts,
        ]);
    }
}

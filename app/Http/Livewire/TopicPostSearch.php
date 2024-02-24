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
use App\Models\TopicRead;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TopicPostSearch extends Component
{
    use WithPagination;

    public string $search = '';

    public Topic $topic;

    /**
     * @var array<mixed>
     */
    protected $queryString = [
        'search' => ['except' => ''],
    ];

    final public function mount(Topic $topic): void
    {
        $this->topic = $topic;
    }

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Post>
     */
    final public function getPostsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $posts = Post::query()
            ->select('posts.*')
            ->with('user', 'user.group')
            ->withCount('likes', 'dislikes', 'authorPosts', 'authorTopics')
            ->withSum('tips', 'bon')
            ->where('topic_id', '=', $this->topic->id)
            ->whereNotIn(
                'topic_id',
                Topic::query()
                    ->whereRelation(
                        'forumPermissions',
                        fn ($query) => $query
                            ->where('group_id', '=', auth()->user()->group_id)
                            ->where('read_topic', '!=', 1)
                    )
                    ->select('id')
            )
            ->join('topics', 'topics.id', '=', 'posts.topic_id')
            ->when($this->search !== '', fn ($query) => $query->where('content', 'LIKE', '%'.$this->search.'%'))
            ->orderBy('created_at')
            ->paginate(25);

        if ($lastPost = $posts->getCollection()->last()) {
            TopicRead::upsert([[
                'topic_id'          => $this->topic->id,
                'user_id'           => auth()->id(),
                'last_read_post_id' => $lastPost->id,
            ]], [
                'topic_id',
                'user_id'
            ], [
                'last_read_post_id' => DB::raw('GREATEST(last_read_post_id, VALUES(last_read_post_id))')
            ]);
        }

        return $posts;
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.topic-post-search', [
            'topic' => $this->topic,
            'posts' => $this->posts,
        ]);
    }
}

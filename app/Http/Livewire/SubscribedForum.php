<?php

declare(strict_types=1);

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
use App\Models\Post;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class SubscribedForum extends Component
{
    use WithPagination;

    /**
     * @return \Illuminate\Pagination\LengthAwarePaginator<Forum>
     */
    #[Computed]
    final public function forums()
    {
        $forums = Forum::query()
            ->withCount('topics', 'posts')
            ->whereRelation('subscribedUsers', 'users.id', '=', auth()->id())
            ->authorized(canReadTopic: true)
            ->orderBy('position')
            ->paginate(25, ['*'], 'subscribedForumsPage');

        $latestPosts = Post::query()
            ->with([
                'user' => fn ($query) => $query->withTrashed(),
                'topic',
            ])
            ->joinSub(
                Post::query()
                    ->selectRaw('MAX(posts.id) AS id, forum_id')
                    ->join('topics', 'posts.topic_id', '=', 'topics.id')
                    ->whereIntegerInRaw('forum_id', $forums->pluck('id'))
                    ->groupBy('forum_id'),
                'latest_posts',
                fn ($join) => $join->on('posts.id', '=', 'latest_posts.id')
            )
            ->get();

        $forums->transform(fn ($forum) => $forum->setRelation('latestPost', $latestPosts->firstWhere('forum_id', '=', $forum->id)));

        return $forums;
    }

    final public function updatedSubscribedForumsPage(): void
    {
        $this->dispatch('paginationChanged');
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.subscribed-forum', [
            'forums' => $this->forums,
        ]);
    }
}

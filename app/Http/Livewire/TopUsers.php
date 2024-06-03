<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\Group;
use App\Models\History;
use App\Models\Peer;
use App\Models\Post;
use App\Models\Thank;
use App\Models\Torrent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TopUsers extends Component
{
    public string $tab = 'uploaders';

    /**
     * @return \Illuminate\Support\Collection<int, Torrent>
     */
    #[Computed(cache: true)]
    final public function uploaders(): \Illuminate\Support\Collection
    {
        return Torrent::with(['user.group'])
            ->select(DB::raw('user_id, COUNT(user_id) as value'))
            ->where('user_id', '!=', User::SYSTEM_USER_ID)
            ->where('anon', '=', false)
            ->groupBy('user_id')
            ->orderByDesc('value')
            ->take(8)
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection<int, History>
     */
    #[Computed(cache: true)]
    final public function downloaders(): \Illuminate\Support\Collection
    {
        return History::with(['user.group'])
            ->select(DB::raw('user_id, count(distinct torrent_id) as value'))
            ->whereNotNull('completed_at')
            ->where('user_id', '!=', User::SYSTEM_USER_ID)
            ->groupBy('user_id')
            ->orderByDesc('value')
            ->take(8)
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection<int, User>
     */
    #[Computed(cache: true)]
    final public function uploaded(): \Illuminate\Support\Collection
    {
        return User::select(['id', 'group_id', 'username', 'uploaded', 'image'])
            ->with('group')
            ->where('id', '!=', User::SYSTEM_USER_ID)
            ->whereNotIn('group_id', Group::select('id')->whereIn('slug', ['banned', 'validating', 'disabled', 'pruned']))
            ->orderByDesc('uploaded')
            ->take(8)
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection<int, User>
     */
    #[Computed(cache: true)]
    final public function downloaded(): \Illuminate\Support\Collection
    {
        return User::select(['id', 'group_id', 'username', 'downloaded', 'image'])
            ->with('group')
            ->where('id', '!=', User::SYSTEM_USER_ID)
            ->whereNotIn('group_id', Group::select('id')->whereIn('slug', ['banned', 'validating', 'disabled', 'pruned']))
            ->orderByDesc('downloaded')
            ->take(8)
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection<int, Peer>
     */
    #[Computed(cache: true)]
    final public function seeders(): \Illuminate\Support\Collection
    {
        return Peer::with(['user.group'])
            ->select(DB::raw('user_id, count(distinct torrent_id) as value'))
            ->where('user_id', '!=', User::SYSTEM_USER_ID)
            ->where('seeder', '=', 1)
            ->where('active', '=', 1)
            ->groupBy('user_id')
            ->orderByDesc('value')
            ->take(8)
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection<int, User>
     */
    #[Computed(cache: true)]
    final public function seedtimes(): \Illuminate\Support\Collection
    {
        return User::withSum('history as seedtime', 'seedtime')
            ->with('group')
            ->where('id', '!=', User::SYSTEM_USER_ID)
            ->whereNotIn('group_id', Group::select('id')->whereIn('slug', ['banned', 'validating', 'disabled', 'pruned']))
            ->orderByDesc('seedtime')
            ->take(8)
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection<int, User>
     */
    #[Computed(cache: true)]
    final public function served(): \Illuminate\Support\Collection
    {
        return User::withCount('uploadSnatches')
            ->with('group')
            ->where('id', '!=', User::SYSTEM_USER_ID)
            ->whereNotIn('group_id', Group::select('id')->whereIn('slug', ['banned', 'validating', 'disabled', 'pruned']))
            ->orderByDesc('upload_snatches_count')
            ->take(8)
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection<int, Comment>
     */
    #[Computed(cache: true)]
    final public function commenters(): \Illuminate\Support\Collection
    {
        return Comment::with(['user.group'])
            ->select(DB::raw('user_id, COUNT(user_id) as value'))
            ->where('user_id', '!=', User::SYSTEM_USER_ID)
            ->where('anon', '=', false)
            ->groupBy('user_id')
            ->orderByRaw('COALESCE(value, 0) DESC')
            ->take(8)
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection<int, Post>
     */
    #[Computed(cache: true)]
    final public function posters(): \Illuminate\Support\Collection
    {
        return Post::with(['user.group'])
            ->select(DB::raw('user_id, COUNT(user_id) as value'))
            ->where('user_id', '!=', User::SYSTEM_USER_ID)
            ->groupBy('user_id')
            ->orderByRaw('COALESCE(value, 0) DESC')
            ->take(8)
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection<int, Thank>
     */
    #[Computed(cache: true)]
    final public function thankers(): \Illuminate\Support\Collection
    {
        return Thank::with(['user.group'])
            ->select(DB::raw('user_id, COUNT(user_id) as value'))
            ->where('user_id', '!=', User::SYSTEM_USER_ID)
            ->groupBy('user_id')
            ->orderByRaw('COALESCE(value, 0) DESC')
            ->take(8)
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection<int, Torrent>
     */
    #[Computed(cache: true)]
    final public function personals(): \Illuminate\Support\Collection
    {
        return Torrent::with(['user.group'])
            ->select(DB::raw('user_id, COUNT(user_id) as value'))
            ->where('user_id', '!=', User::SYSTEM_USER_ID)
            ->where('anon', '=', false)
            ->where('personal_release', '=', 1)
            ->groupBy('user_id')
            ->orderByDesc('value')
            ->take(8)
            ->get();
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.top-users');
    }
}

<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationSearch extends Component
{
    use WithPagination;

    public bool $bon_gifts = false;

    public bool $comment = false;

    public bool $comment_tags = false;

    public bool $followers = false;

    public bool $posts = false;

    public bool $post_tags = false;

    public bool $post_tips = false;

    public bool $request_bounties = false;

    public bool $request_claims = false;

    public bool $request_fills = false;

    public bool $request_approvals = false;

    public bool $request_rejections = false;

    public bool $request_unclaims = false;

    public bool $reseed_requests = false;

    public bool $thanks = false;

    public bool $upload_tips = false;

    public bool $topics = false;

    public bool $unfollows = false;

    public bool $uploads = false;

    public int $perPage = 25;

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    final public function paginationView(): string
    {
        return 'vendor.pagination.livewire-pagination';
    }

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function getNotificationsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return \auth()->user()->notifications()
            ->when($this->bon_gifts, function ($query) {
                $query->where('type', '=', \App\Notifications\NewBon::class);
            })
            ->when($this->comment, function ($query) {
                $query->where('type', '=', \App\Notifications\NewComment::class);
            })
            ->when($this->comment_tags, function ($query) {
                $query->where('type', '=', \App\Notifications\NewCommentTag::class);
            })
            ->when($this->followers, function ($query) {
                $query->where('type', '=', \App\Notifications\NewFollow::class);
            })
            ->when($this->posts, function ($query) {
                $query->where('type', '=', \App\Notifications\NewPost::class);
            })
            ->when($this->post_tags, function ($query) {
                $query->where('type', '=', \App\Notifications\NewPostTag::class);
            })
            ->when($this->post_tips, function ($query) {
                $query->where('type', '=', \App\Notifications\NewPostTip::class);
            })
            ->when($this->request_bounties, function ($query) {
                $query->where('type', '=', \App\Notifications\NewRequestBounty::class);
            })
            ->when($this->request_claims, function ($query) {
                $query->where('type', '=', \App\Notifications\NewRequestClaim::class);
            })
            ->when($this->request_fills, function ($query) {
                $query->where('type', '=', \App\Notifications\NewRequestFill::class);
            })
            ->when($this->request_approvals, function ($query) {
                $query->where('type', '=', \App\Notifications\NewRequestFillApprove::class);
            })
            ->when($this->request_rejections, function ($query) {
                $query->where('type', '=', \App\Notifications\NewRequestFillReject::class);
            })
            ->when($this->request_unclaims, function ($query) {
                $query->where('type', '=', \App\Notifications\NewRequestUnclaim::class);
            })
            ->when($this->reseed_requests, function ($query) {
                $query->where('type', '=', \App\Notifications\NewReseedRequest::class);
            })
            ->when($this->thanks, function ($query) {
                $query->where('type', '=', \App\Notifications\NewThank::class);
            })
            ->when($this->upload_tips, function ($query) {
                $query->where('type', '=', \App\Notifications\NewUploadTip::class);
            })
            ->when($this->topics, function ($query) {
                $query->where('type', '=', \App\Notifications\NewTopic::class);
            })
            ->when($this->unfollows, function ($query) {
                $query->where('type', '=', \App\Notifications\NewUnfollow::class);
            })
            ->when($this->uploads, function ($query) {
                $query->where('type', '=', \App\Notifications\NewUpload::class);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    final public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return \view('livewire.notification-search', [
            'user'               => User::with(['group'])->findOrFail(\auth()->user()->id),
            'notifications'      => $this->notifications,
        ]);
    }
}

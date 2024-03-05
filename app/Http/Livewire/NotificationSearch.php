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
use App\Traits\LivewireSort;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationSearch extends Component
{
    use LivewireSort;
    use WithPagination;

    #[Url]
    public bool $bon_gifts = false;

    #[Url]
    public bool $comment = false;

    #[Url]
    public bool $comment_tags = false;

    #[Url]
    public bool $followers = false;

    #[Url]
    public bool $posts = false;

    #[Url]
    public bool $post_tags = false;

    #[Url]
    public bool $post_tips = false;

    #[Url]
    public bool $request_bounties = false;

    #[Url]
    public bool $request_claims = false;

    #[Url]
    public bool $request_fills = false;

    #[Url]
    public bool $request_approvals = false;

    #[Url]
    public bool $request_rejections = false;

    #[Url]
    public bool $request_unclaims = false;

    #[Url]
    public bool $reseed_requests = false;

    #[Url]
    public bool $thanks = false;

    #[Url]
    public bool $upload_tips = false;

    #[Url]
    public bool $topics = false;

    #[Url]
    public bool $unfollows = false;

    #[Url]
    public bool $uploads = false;

    #[Url]
    public int $perPage = 25;

    #[Url]
    public string $sortField = 'created_at';

    #[Url]
    public string $sortDirection = 'desc';

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<\Illuminate\Notifications\DatabaseNotification>
     */
    #[Computed]
    final public function notifications(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return auth()->user()?->notifications()
            ->select('*')
            ->selectRaw("CASE WHEN read_at IS NULL THEN 'FALSE' ELSE 'TRUE' END as is_read")
            ->where(function ($query): void {
                $query->when($this->bon_gifts, function ($query): void {
                    $query->orWhere('type', '=', \App\Notifications\NewBon::class);
                })
                    ->when($this->comment, function ($query): void {
                        $query->orWhere('type', '=', \App\Notifications\NewComment::class);
                    })
                    ->when($this->comment_tags, function ($query): void {
                        $query->orWhere('type', '=', \App\Notifications\NewCommentTag::class);
                    })
                    ->when($this->followers, function ($query): void {
                        $query->orWhere('type', '=', \App\Notifications\NewFollow::class);
                    })
                    ->when($this->posts, function ($query): void {
                        $query->orWhere('type', '=', \App\Notifications\NewPost::class);
                    })
                    ->when($this->post_tags, function ($query): void {
                        $query->orWhere('type', '=', \App\Notifications\NewPostTag::class);
                    })
                    ->when($this->post_tips, function ($query): void {
                        $query->orWhere('type', '=', \App\Notifications\NewPostTip::class);
                    })
                    ->when($this->request_bounties, function ($query): void {
                        $query->orWhere('type', '=', \App\Notifications\NewRequestBounty::class);
                    })
                    ->when($this->request_claims, function ($query): void {
                        $query->orWhere('type', '=', \App\Notifications\NewRequestClaim::class);
                    })
                    ->when($this->request_fills, function ($query): void {
                        $query->orWhere('type', '=', \App\Notifications\NewRequestFill::class);
                    })
                    ->when($this->request_approvals, function ($query): void {
                        $query->orWhere('type', '=', \App\Notifications\NewRequestFillApprove::class);
                    })
                    ->when($this->request_rejections, function ($query): void {
                        $query->orWhere('type', '=', \App\Notifications\NewRequestFillReject::class);
                    })
                    ->when($this->request_unclaims, function ($query): void {
                        $query->orWhere('type', '=', \App\Notifications\NewRequestUnclaim::class);
                    })
                    ->when($this->reseed_requests, function ($query): void {
                        $query->orWhere('type', '=', \App\Notifications\NewReseedRequest::class);
                    })
                    ->when($this->thanks, function ($query): void {
                        $query->orWhere('type', '=', \App\Notifications\NewThank::class);
                    })
                    ->when($this->upload_tips, function ($query): void {
                        $query->orWhere('type', '=', \App\Notifications\NewUploadTip::class);
                    })
                    ->when($this->topics, function ($query): void {
                        $query->orWhere('type', '=', \App\Notifications\NewTopic::class);
                    })
                    ->when($this->unfollows, function ($query): void {
                        $query->orWhere('type', '=', \App\Notifications\NewUnfollow::class);
                    })
                    ->when($this->uploads, function ($query): void {
                        $query->orWhere('type', '=', \App\Notifications\NewUpload::class);
                    });
            })
            ->reorder()
            ->orderBy('is_read')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.notification-search', [
            'user'          => User::with(['group'])->findOrFail(auth()->id()),
            'notifications' => $this->notifications,
        ]);
    }
}

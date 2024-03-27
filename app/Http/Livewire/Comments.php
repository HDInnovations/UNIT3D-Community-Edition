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

use App\Achievements\UserMade100Comments;
use App\Achievements\UserMade200Comments;
use App\Achievements\UserMade300Comments;
use App\Achievements\UserMade400Comments;
use App\Achievements\UserMade500Comments;
use App\Achievements\UserMade50Comments;
use App\Achievements\UserMade600Comments;
use App\Achievements\UserMade700Comments;
use App\Achievements\UserMade800Comments;
use App\Achievements\UserMade900Comments;
use App\Achievements\UserMadeComment;
use App\Achievements\UserMadeTenComments;
use App\Models\Torrent;
use App\Models\User;
use App\Notifications\NewComment;
use App\Notifications\NewCommentTag;
use App\Repositories\ChatRepository;
use App\Traits\CastLivewireProperties;
use Illuminate\Support\Facades\Notification;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use voku\helper\AntiXSS;

class Comments extends Component
{
    use CastLivewireProperties;

    use WithPagination;

    protected ChatRepository $chatRepository;

    public ?User $user;

    public $model;

    public bool $anon = false;

    public int $perPage = 10;

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public $newCommentState = [
        'content' => '',
    ];

    protected $validationAttributes = [
        'newCommentState.content' => 'comment',
    ];

    final public function boot(ChatRepository $chatRepository): void
    {
        $this->chatRepository = $chatRepository;
    }

    final public function mount(): void
    {
        $this->user = auth()->user();
    }

    final public function updating(string $field, mixed &$value): void
    {
        $this->castLivewireProperties($field, $value);
    }

    final public function taggedUsers(): array
    {
        preg_match_all('/@([\w\-]+)/', implode('', $this->newCommentState), $matches);

        return $matches[1];
    }

    final public function loadMore(): void
    {
        $this->perPage += 10;
    }

    /// TODO: Find a better data structure to avoid this mess of exception cases
    final public function postComment(): void
    {
        // Set Polymorhic Model Name
        $modelName = str()->snake(class_basename($this->model), ' ');

        if ($modelName !== 'ticket' && $this->user->can_comment === false) {
            $this->dispatch('error', type: 'error', message: __('comment.rights-revoked'));

            return;
        }

        if (strtolower(class_basename($this->model)) === 'torrent' && $this->model->status !== Torrent::APPROVED) {
            $this->dispatch('error', type: 'error', message: __('comment.torrent-status'));

            return;
        }

        $this->validate([
            'newCommentState.content' => 'required',
        ]);

        $comment = $this->model->comments()->make((new AntiXSS())->xss_clean($this->newCommentState));
        $comment->user()->associate($this->user);
        $comment->anon = $this->anon;
        $comment->save();

        // New Comment Notification
        switch ($modelName) {
            case 'ticket':
                $ticket = $this->model;

                if ($this->user->id !== $ticket->staff_id && $ticket->staff_id !== null) {
                    User::find($ticket->staff_id)->notify(new NewComment($modelName, $comment));
                }

                if ($this->user->id !== $ticket->user_id) {
                    User::find($ticket->user_id)->notify(new NewComment($modelName, $comment));
                }

                break;
            case 'article':
            case 'playlist':
            case 'torrent request':
            case 'torrent':
                if ($this->user->id !== $this->model->user_id) {
                    User::find($this->model->user_id)->notify(new NewComment($modelName, $comment));
                }

                break;
        }

        // User Tagged Notification
        $users = User::whereIn('username', $this->taggedUsers())->get();
        Notification::sendNow($users, new NewCommentTag($modelName, $comment));

        // Auto Shout
        $profileUrl = href_profile($this->user);

        $modelUrl = match ($modelName) {
            'article'         => href_article($this->model),
            'collection'      => href_collection($this->model),
            'playlist'        => href_playlist($this->model),
            'torrent request' => href_request($this->model),
            'torrent'         => href_torrent($this->model),
            default           => "#"
        };

        if ($modelName !== 'ticket') {
            if ($comment->anon == 0) {
                $this->chatRepository->systemMessage(
                    sprintf(
                        '[url=%s]%s[/url] has left a comment on '.$modelName.' [url=%s]%s[/url]',
                        $profileUrl,
                        $this->user->username,
                        $modelUrl,
                        $this->model->name ?? $this->model->title
                    )
                );
            } else {
                $this->chatRepository->systemMessage(
                    sprintf(
                        'An anonymous user has left a comment on '.$modelName.' [url=%s]%s[/url]',
                        $modelUrl,
                        $this->model->name ?? $this->model->title
                    )
                );
            }
        }

        // Achievements
        if ($comment->anon == 0 && $modelName !== 'ticket') {
            $this->user->unlock(new UserMadeComment());
            $this->user->addProgress(new UserMadeTenComments(), 1);
            $this->user->addProgress(new UserMade50Comments(), 1);
            $this->user->addProgress(new UserMade100Comments(), 1);
            $this->user->addProgress(new UserMade200Comments(), 1);
            $this->user->addProgress(new UserMade300Comments(), 1);
            $this->user->addProgress(new UserMade400Comments(), 1);
            $this->user->addProgress(new UserMade500Comments(), 1);
            $this->user->addProgress(new UserMade600Comments(), 1);
            $this->user->addProgress(new UserMade700Comments(), 1);
            $this->user->addProgress(new UserMade800Comments(), 1);
            $this->user->addProgress(new UserMade900Comments(), 1);
        }

        $this->newCommentState = [
            'content' => '',
        ];

        $this->gotoPage(1);
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Comment>
     */
    #[Computed]
    final public function comments(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->model
            ->comments()
            ->with(['user:id,username,group_id,image,title', 'user.group', 'children.user:id,username,group_id,image,title', 'children.user.group'])
            ->parent()
            ->latest()
            ->paginate($this->perPage);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.comments', [
            'comments' => $this->comments,
        ]);
    }
}

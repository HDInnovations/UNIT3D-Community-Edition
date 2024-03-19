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
use App\Models\User;
use App\Notifications\NewComment;
use App\Notifications\NewCommentTag;
use App\Repositories\ChatRepository;
use App\Traits\CastLivewireProperties;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use voku\helper\AntiXSS;

class Comment extends Component
{
    use CastLivewireProperties;

    protected ChatRepository $chatRepository;

    public $comment;

    public bool $anon = false;

    public ?User $user;

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    protected $validationAttributes = [
        'replyState.content' => 'reply',
    ];

    public $isReplying = false;

    public $replyState = [
        'content' => '',
    ];

    public $isEditing = false;

    public $editState = [
        'content' => '',
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
        preg_match_all('/@([\w\-]+)/', implode('', $this->editState), $matches);

        return $matches[1];
    }

    final public function updatedIsEditing($isEditing): void
    {
        if (!$isEditing) {
            return;
        }

        $this->editState = [
            'content' => $this->comment->content,
        ];
    }

    final public function editComment(): void
    {
        if (auth()->id() == $this->comment->user_id || auth()->user()->group->is_modo) {
            $this->comment->update((new AntiXSS())->xss_clean($this->editState));
            $this->isEditing = false;
        } else {
            $this->dispatch('error', type: 'error', message: 'Permission Denied!');
        }
    }

    final public function deleteComment(): void
    {
        if ((auth()->id() == $this->comment->user_id || auth()->user()->group->is_modo) && $this->comment->children()->doesntExist()) {
            $this->comment->delete();
            $this->dispatch('refresh');
        } else {
            $this->dispatch('error', type: 'error', message: 'Permission Denied!');
        }
    }

    final public function postReply(): void
    {
        // Set Polymorphic Model Name
        $modelName = str()->snake(class_basename($this->comment->commentable_type), ' ');

        if ($modelName !== 'ticket' && auth()->user()->can_comment === false) {
            $this->dispatch('error', type: 'error', message: __('comment.rights-revoked'));

            return;
        }

        if (!$this->comment->isParent()) {
            return;
        }

        $this->validate([
            'replyState.content' => 'required',
        ]);

        $reply = $this->comment->children()->make((new AntiXSS())->xss_clean($this->replyState));
        $reply->user()->associate(auth()->user());
        $reply->commentable()->associate($this->comment->commentable);
        $reply->anon = $this->anon;
        $reply->save();

        // New Comment Notification
        switch ($modelName) {
            case 'ticket':
                $ticket = $this->comment->commentable;

                if ($this->user->id !== $ticket->staff_id && $ticket->staff_id !== null) {
                    User::find($ticket->staff_id)->notify(new NewComment($modelName, $reply));
                }

                if ($this->user->id !== $ticket->user_id) {
                    User::find($ticket->user_id)->notify(new NewComment($modelName, $reply));
                }

                if (!\in_array($this->comment->user_id, [$ticket->staff_id, $ticket->user_id, $this->user->id])) {
                    User::find($this->comment->user_id)->notify(new NewComment($modelName, $reply));
                }

                break;
            case 'article':
            case 'playlist':
            case 'torrent request':
            case 'torrent':
                if ($this->user->id !== $this->comment->user_id) {
                    User::find($this->comment->user_id)->notify(new NewComment($modelName, $reply));
                }

                break;
        }

        // User Tagged Notification
        $users = User::whereIn('username', $this->taggedUsers())->get();
        Notification::sendNow($users, new NewCommentTag($modelName, $reply));

        // Auto Shout
        $profileUrl = href_profile($this->user);

        $modelUrl = match ($modelName) {
            'article'         => href_article($this->comment->commentable),
            'collection'      => href_collection($this->comment->commentable),
            'playlist'        => href_playlist($this->comment->commentable),
            'torrent request' => href_request($this->comment->commentable),
            'torrent'         => href_torrent($this->comment->commentable),
            default           => "#"
        };

        if ($modelName !== 'ticket') {
            if ($reply->anon == 0) {
                $this->chatRepository->systemMessage(
                    sprintf(
                        '[url=%s]%s[/url] has left a comment on '.$modelName.' [url=%s]%s[/url]',
                        $profileUrl,
                        $this->user->username,
                        $modelUrl,
                        $this->comment->commentable->name ?? $this->comment->commentable->title
                    )
                );
            } else {
                $this->chatRepository->systemMessage(
                    sprintf(
                        'An anonymous user has left a comment on '.$modelName.' [url=%s]%s[/url]',
                        $modelUrl,
                        $this->comment->commentable->name ?? $this->comment->commentable->title
                    )
                );
            }
        }

        // Achievements
        if ($reply->anon == 0 && $modelName !== 'ticket') {
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

        $this->replyState = [
            'content' => '',
        ];

        $this->isReplying = false;

        $this->dispatch('refresh')->self();
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.comment');
    }
}

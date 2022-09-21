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
use App\Repositories\TaggedUserRepository;
use Livewire\Component;
use voku\helper\AntiXSS;

class Comment extends Component
{
    public $comment;

    public $anon = false;

    private TaggedUserRepository $taggedUserRepository;

    public \Illuminate\Contracts\Auth\Authenticatable|\App\Models\User $user;

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

    final public function mount(TaggedUserRepository $taggedUserRepository): void
    {
        $this->taggedUserRepository = $taggedUserRepository;
        $this->user = \auth()->user();
    }

    final public function updatedIsEditing($isEditing): void
    {
        if (! $isEditing) {
            return;
        }

        $this->editState = [
            'content' => $this->comment->content,
        ];
    }

    final public function editComment(): void
    {
        if (\auth()->user()->id !== $this->comment->user_id || ! \auth()->user()->group->is_modo) {
            $this->dispatchBrowserEvent('error', ['type' => 'error',  'message' => 'Permission Denied!']);

            return;
        }

        $this->comment->update((new AntiXSS())->xss_clean($this->editState));

        $this->isEditing = false;
    }

    final public function deleteComment(): void
    {
        if (\auth()->user()->id !== $this->comment->user_id || ! \auth()->user()->group->is_modo) {
            $this->dispatchBrowserEvent('error', ['type' => 'error',  'message' => 'Permission Denied!']);

            return;
        }

        $this->comment->delete();

        $this->emitUp('refresh');
    }

    final public function postReply(): void
    {
        if (\auth()->user()->can_comment === 0) {
            $this->dispatchBrowserEvent('error', ['type' => 'error',  'message' => \trans('comment.rights-revoked')]);

            return;
        }

        if (! $this->comment->isParent()) {
            return;
        }

        $this->validate([
            'replyState.content' => 'required',
        ]);

        $reply = $this->comment->children()->make((new AntiXSS())->xss_clean($this->replyState));
        $reply->user()->associate(\auth()->user());
        $reply->commentable()->associate($this->comment->commentable);
        $reply->anon = $this->anon;
        $reply->save();

        $this->replyState = [
            'content' => '',
        ];

        // Achievements
        if ($reply->anon === 0) {
            $this->user->unlock(new UserMadeComment(), 1);
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

        //Notification
        if ($this->user->id !== $this->comment->user_id) {
            User::find($this->comment->user_id)->notify(new NewComment($this->comment, $reply));
        }

        // Tagging
        if ($this->taggedUserRepository->hasTags($this->replyState)) {
            if ($this->user->group->is_modo && $this->taggedUserRepository->contains($this->replyState, '@here')) {
                $users = \collect([]);

                $this->comment->children()->get()->each(function ($c) use ($users) {
                    $users->push($c->user);
                });
                $this->taggedUserRepository->messageCommentUsers(
                    $this->comment,
                    $users,
                    $this->user,
                    'Staff',
                    $reply
                );
            } else {
                $sender = $reply->anon !== 0 ? $this->user->username : 'Anonymous';
                $this->taggedUserRepository->messageTaggedCommentUsers(
                    $this->comment,
                    $this->replyState[],
                    $this->user,
                    $sender,
                    $reply
                );
            }
        }

        $this->isReplying = false;

        $this->emitSelf('refresh');
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return \view('livewire.comment');
    }
}

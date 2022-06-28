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
use App\Repositories\ChatRepository;
use App\Repositories\TaggedUserRepository;
use Livewire\Component;
use Livewire\WithPagination;
use voku\helper\AntiXSS;

class Comments extends Component
{
    use WithPagination;

    private TaggedUserRepository $taggedUserRepository;

    private ChatRepository $chatRepository;

    private ?\Illuminate\Contracts\Auth\Authenticatable $user;

    public $model;

    public $anon = 0;

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

    final public function mount(TaggedUserRepository $taggedUserRepository, ChatRepository $chatRepository): void
    {
        $this->taggedUserRepository = $taggedUserRepository;
        $this->chatRepository = $chatRepository;
        $this->user = \auth()->user();
    }

    final public function loadMore()
    {
        $this->perPage += 10;
    }

    final public function postComment(): void
    {
        if ($this->user->can_comment === 0) {
            $this->dispatchBrowserEvent('error', ['type' => 'error',  'message' => \trans('comment.rights-revoked')]);

            return;
        }

        $this->validate([
            'newCommentState.content' => 'required',
        ]);

        $comment = $this->model->comments()->make((new AntiXSS())->xss_clean($this->newCommentState));
        $comment->user()->associate($this->user);
        $comment->anon = $this->anon;
        $comment->save();

        $this->newCommentState = [
            'content' => '',
        ];

        // Achievements
        if ($comment->anon === 0) {
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
        /*if ($this->user->id !== $this->model->user_id) {
            User::find($this->model->user_id)->notify(new NewComment($this->model, $comment));
        }

        // Auto Shout
        $profileUrl = \href_profile($this->user);

        if ($comment->anon === 0) {
            $this->chatRepository->systemMessage(
                \sprintf('[url=%s]%s[/url] has left a comment on Torrent [url=%s]%s[/url]', $profileUrl, $this->user->username, $torrentUrl, $this->model->name)
            );
        } else {
            $this->chatRepository->systemMessage(
                \sprintf('An anonymous user has left a comment on torrent [url=%s]%s[/url]', $torrentUrl, $this->model->name)
            );
        }

        // Tagging
        if ($this->taggedUserRepository->hasTags($this->newCommentState)) {
            if ($this->taggedUserRepository->contains($this->newCommentState, '@here') && $this->user->group->is_modo) {
                $users = \collect([]);

                $this->model->comments()->get()->each(function ($c) use ($users) {
                    $users->push($c->user);
                });
                $this->taggedUserRepository->messageCommentUsers(
                    $this->model,
                    $users,
                    $this->user,
                    'Staff',
                    $comment
                );
            } else {
                $sender = $comment->anon !== 0 ? $this->user->username : 'Anonymous';
                $this->taggedUserRepository->messageTaggedCommentUsers(
                    $this->model,
                    $this->newCommentState,
                    $this->user,
                    $sender,
                    $comment
                );
            }
        }*/

        $this->goToPage(1);
    }

    final public function getCommentsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->model
            ->comments()
            ->with('user', 'children.user', 'children.children')
            ->parent()
            ->latest()
            ->paginate($this->perPage);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return \view('livewire.comments', [
            'comments' => $this->comments,
        ]);
    }
}

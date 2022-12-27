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
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithPagination;
use voku\helper\AntiXSS;

class Comments extends Component
{
    use WithPagination;

    public \Illuminate\Contracts\Auth\Authenticatable|\App\Models\User $user;

    public $model;

    public $anon = false;

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

    final public function mount(): void
    {
        $this->user = \auth()->user();
    }

    final public function taggedUsers(): array
    {
        \preg_match_all('/@([\w\-]+)/', \implode('', $this->newCommentState), $matches);

        return $matches[1];
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

        // New Comment Notification
        if ($this->user->id !== $this->model->user_id) {
            User::find($this->model->user_id)->notify(new NewComment(\strtolower(\class_basename($this->model)), $comment));
        }

        // User Tagged Notification
        if ($this->user->id !== $this->model->user_id) {
            $users = User::whereIn('username', $this->taggedUsers())->get();
            Notification::sendNow($users, new NewCommentTag(\strtolower(\class_basename($this->model)), $comment));
        }

        $this->newCommentState = [
            'content' => '',
        ];

        $this->gotoPage(1);
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

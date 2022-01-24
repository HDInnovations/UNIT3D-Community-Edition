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
use Livewire\Component;

class Comment extends Component
{
    public $comment;

    public $anon;

    protected $listeners = [
        'refresh' => '$refresh'
    ];

    protected $validationAttributes = [
        'replyState.body' => 'reply'
    ];

    public $isReplying = false;

    public $replyState = [
        'body' => ''
    ];

    public $isEditing = false;

    public $editState = [
        'body' => ''
    ];

    final public function updatedIsEditing($isEditing): void
    {
        if (!$isEditing) {
            return;
        }

        $this->editState = [
            'body' => $this->comment->body
        ];
    }

    final public function editComment(): void
    {
        if (\auth()->user()->id !== $this->comment->user_id || ! \auth()->user()->group->is_modo) {
            $this->dispatchBrowserEvent('error', ['type' => 'error',  'message' => 'Permission Denied!']);

            return;
        }

        $this->comment->update($this->editState);

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
            'replyState.body' => 'required'
        ]);

        $reply = $this->comment->children()->make($this->replyState);
        $reply->user()->associate(\auth()->user());
        $reply->commentable()->associate($this->comment->commentable);
        $reply->anon = $this->anon;
        $reply->save();

        if ($this->comment->anon === 0) {
            \auth()->user()->unlock(new UserMadeComment(), 1);
            \auth()->user()->addProgress(new UserMadeTenComments(), 1);
            \auth()->user()->addProgress(new UserMade50Comments(), 1);
            \auth()->user()->addProgress(new UserMade100Comments(), 1);
            \auth()->user()->addProgress(new UserMade200Comments(), 1);
            \auth()->user()->addProgress(new UserMade300Comments(), 1);
            \auth()->user()->addProgress(new UserMade400Comments(), 1);
            \auth()->user()->addProgress(new UserMade500Comments(), 1);
            \auth()->user()->addProgress(new UserMade600Comments(), 1);
            \auth()->user()->addProgress(new UserMade700Comments(), 1);
            \auth()->user()->addProgress(new UserMade800Comments(), 1);
            \auth()->user()->addProgress(new UserMade900Comments(), 1);
        }

        $this->replyState = [
            'body' => ''
        ];

        $this->isReplying = false;

        $this->emitSelf('refresh');
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return \view('livewire.comment');
    }
}

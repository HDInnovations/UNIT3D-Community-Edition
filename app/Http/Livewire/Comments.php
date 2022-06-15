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

use Livewire\Component;
use Livewire\WithPagination;

class Comments extends Component
{
    use WithPagination;

    public $model;

    public $anon;

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public $newCommentState = [
        'content' => '',
    ];

    protected $validationAttributes = [
        'newCommentState.content' => 'comment',
    ];

    final public function postComment(): void
    {
        if (\auth()->user()->can_comment === 0) {
            $this->dispatchBrowserEvent('error', ['type' => 'error',  'message' => \trans('comment.rights-revoked')]);

            return;
        }

        $this->validate([
            'newCommentState.content' => 'required',
        ]);

        $comment = $this->model->comments()->make($this->newCommentState);
        $comment->user()->associate(\auth()->user());
        $comment->anon = $this->anon;
        $comment->save();

        $this->newCommentState = [
            'content' => '',
        ];

        $this->goToPage(1);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $comments = $this->model
            ->comments()
            ->with('user', 'children.user', 'children.children')
            ->parent()
            ->latest()
            ->paginate(10);

        return \view('livewire.comments', [
            'comments' => $comments,
        ]);
    }
}

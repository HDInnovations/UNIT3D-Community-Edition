<?php

/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Notifications\NewBon;
use App\Notifications\NewComment;
use App\Notifications\NewCommentTag;
use App\Notifications\NewFollow;
use App\Notifications\NewPost;
use App\Notifications\NewPostTag;
use App\Notifications\NewPostTip;
use App\Notifications\NewRequestClaim;
use App\Notifications\NewRequestFill;
use App\Notifications\NewRequestFillApprove;
use App\Notifications\NewRequestFillReject;
use App\Notifications\NewRequestUnclaim;
use App\Notifications\NewReseedRequest;
use App\Notifications\NewThank;
use App\Notifications\NewTopic;
use App\Notifications\NewUpload;
use App\Notifications\NewUploadTip;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

final class NotificationController extends Controller
{
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private Factory $viewFactory;
    /**
     * @var \Illuminate\Routing\Redirector
     */
    private Redirector $redirector;

    public function __construct(Factory $viewFactory, Redirector $redirector)
    {
        $this->viewFactory = $viewFactory;
        $this->redirector = $redirector;
    }

    /**
     * Show All Notifications.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request): Factory
    {
        $notifications = $request->user()->notifications()->paginate(25);

        return $this->viewFactory->make('notification.index', ['notifications' => $notifications]);
    }

    /**
     * Uses Input's To Put Together A Search.
     *
     * @param \Illuminate\Http\Request $request
     *
     *
     * @throws \Throwable
     * @return mixed[]|string
     */
    public function faceted(Request $request)
    {
        $user = $request->user();

        $notification = $user->notifications();

        if ($request->has('bon_gifts') && $request->input('bon_gifts') != null) {
            $notification->where('type', '=', NewBon::class);
        }

        if ($request->has('comments') && $request->input('comments') != null) {
            $notification->where('type', '=', NewComment::class);
        }

        if ($request->has('comment_tags') && $request->input('comment_tags') != null) {
            $notification->where('type', '=', NewCommentTag::class);
        }

        if ($request->has('followers') && $request->input('followers') != null) {
            $notification->where('type', '=', NewFollow::class);
        }

        if ($request->has('posts') && $request->input('posts') != null) {
            $notification->where('type', '=', NewPost::class);
        }

        if ($request->has('post_tags') && $request->input('post_tags') != null) {
            $notification->where('type', '=', NewPostTag::class);
        }

        if ($request->has('post_tips') && $request->input('post_tips') != null) {
            $notification->where('type', '=', NewPostTip::class);
        }

        if ($request->has('request_bounties') && $request->input('request_bounties') != null) {
            $notification->where('type', '=', 'App\Notifications\NewRequestCounty');
        }

        if ($request->has('request_claims') && $request->input('request_claims') != null) {
            $notification->where('type', '=', NewRequestClaim::class);
        }

        if ($request->has('request_fills') && $request->input('request_fills') != null) {
            $notification->where('type', '=', NewRequestFill::class);
        }

        if ($request->has('request_approvals') && $request->input('request_approvals') != null) {
            $notification->where('type', '=', NewRequestFillApprove::class);
        }

        if ($request->has('request_rejections') && $request->input('request_rejections') != null) {
            $notification->where('type', '=', NewRequestFillReject::class);
        }

        if ($request->has('request_unclaims') && $request->input('request_unclaims') != null) {
            $notification->where('type', '=', NewRequestUnclaim::class);
        }

        if ($request->has('reseed_requests') && $request->input('reseed_requests') != null) {
            $notification->where('type', '=', NewReseedRequest::class);
        }

        if ($request->has('thanks') && $request->input('thanks') != null) {
            $notification->where('type', '=', NewThank::class);
        }

        if ($request->has('upload_tips') && $request->input('upload_tips') != null) {
            $notification->where('type', '=', NewUploadTip::class);
        }

        if ($request->has('topics') && $request->input('topics') != null) {
            $notification->where('type', '=', NewTopic::class);
        }

        if ($request->has('unfollows') && $request->input('unfollows') != null) {
            $notification->where('type', '=', 'App\Notifications\NewUnfollowt');
        }

        if ($request->has('uploads') && $request->input('uploads') != null) {
            $notification->where('type', '=', NewUpload::class);
        }

        $notifications = $notification->paginate(25);

        return $this->viewFactory->make('notification.results', [
            'user'            => $user,
            'notifications' => $notifications,
        ])->render();
    }

    /**
     * Show A Notification And Mark As Read.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(Request $request, $id): RedirectResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return $this->redirector->to($notification->data['url'])
            ->withSuccess('Notification Marked As Read!');
    }

    /**
     * Set A Notification To Read.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function update(Request $request, $id)
    {
        $notification = $request->user()->notifications()->where('id', '=', $id)->first();

        if (! $notification) {
            return $this->redirector->route('notifications.index')
                ->withErrors('Notification Does Not Exist!');
        }

        if ($notification->read_at != null) {
            return $this->redirector->route('notifications.index')
                ->withErrors('Notification Already Marked As Read!');
        }

        $notification->markAsRead();

        return $this->redirector->route('notifications.index')
            ->withSuccess('Notification Marked As Read!');
    }

    /**
     * Mass Update All Notification's To Read.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAll(Request $request): RedirectResponse
    {
        $current = new Carbon();
        $request->user()->unreadNotifications()->update(['read_at' => $current]);

        return $this->redirector->route('notifications.index')
            ->withSuccess('All Notifications Marked As Read!');
    }

    /**
     * Delete A Notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        $request->user()->notifications()->findOrFail($id)->delete();

        return $this->redirector->route('notifications.index')
            ->withSuccess('Notification Deleted!');
    }

    /**
     * Mass Delete All Notification's.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyAll(Request $request): RedirectResponse
    {
        $request->user()->notifications()->delete();

        return $this->redirector->route('notifications.index')
            ->withSuccess('All Notifications Deleted!');
    }
}

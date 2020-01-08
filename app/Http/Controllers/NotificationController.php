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

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Throwable;
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
use App\Notifications\NewUploadTip;
use App\Notifications\NewTopic;
use App\Notifications\NewUpload;
use Illuminate\Http\RedirectResponse;
use Exception;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Show All Notifications.
     *
     * @param Request $request
     *
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->paginate(25);

        return view('notification.index', ['notifications' => $notifications]);
    }

    /**
     * Uses Input's To Put Together A Search.
     *
     * @param Request $request
     *
     * @throws Throwable
     *
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

        return view('notification.results', [
            'user'            => $user,
            'notifications'   => $notifications,
        ])->render();
    }

    /**
     * Show A Notification And Mark As Read.
     *
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     */
    public function show(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->to($notification->data['url'])
            ->withSuccess('Notification Marked As Read!');
    }

    /**
     * Set A Notification To Read.
     *
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function update(Request $request, $id)
    {
        $notification = $request->user()->notifications()->where('id', '=', $id)->first();

        if (!$notification) {
            return redirect()->route('notifications.index')
                ->withErrors('Notification Does Not Exist!');
        }

        if ($notification->read_at != null) {
            return redirect()->route('notifications.index')
                ->withErrors('Notification Already Marked As Read!');
        }

        $notification->markAsRead();

        return redirect()->route('notifications.index')
            ->withSuccess('Notification Marked As Read!');
    }

    /**
     * Mass Update All Notification's To Read.
     *
     * @param Request $request
     *
     * @throws Exception
     *
     * @return RedirectResponse
     */
    public function updateAll(Request $request): \Illuminate\Http\RedirectResponse
    {
        $current = new Carbon();
        $request->user()->unreadNotifications()->update(['read_at' => $current]);

        return redirect()->route('notifications.index')
            ->withSuccess('All Notifications Marked As Read!');
    }

    /**
     * Delete A Notification.
     *
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     */
    public function destroy(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $request->user()->notifications()->findOrFail($id)->delete();

        return redirect()->route('notifications.index')
            ->withSuccess('Notification Deleted!');
    }

    /**
     * Mass Delete All Notification's.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function destroyAll(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->user()->notifications()->delete();

        return redirect()->route('notifications.index')
            ->withSuccess('All Notifications Deleted!');
    }
}

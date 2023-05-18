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

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\Subscription;
use App\Models\Topic;
use Illuminate\Http\Request;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\SubscriptionControllerTest
 */
class SubscriptionController extends Controller
{
    /**
     * Search For Subscribed Forums & Topics.
     */
    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();

        return view('forum.subscriptions', [
            'user' => $user,
        ]);
    }

    /**
     * Store a subscription.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'forum_id' => 'sometimes|prohibits:topic_id,required_without:topic_id,exists:forums,id',
            'topic_id' => 'sometimes|prohibits:forum_id,required_without:forum_id,exists:topics,id',
        ]);

        switch (true) {
            case $request->has('forum_id') === true:
                abort_unless(
                    Forum::query()
                        ->where('id', '=', $request->forum_id)
                        ->whereRelation('permissions', [
                            ['show_forum', '=', 1],
                            ['group_id', '=', $user->group_id],
                        ])
                        ->exists(),
                    403
                );
                $request->user()->subscribedForums()->attach($request->forum_id);

                return back()
                    ->withSuccess('You are now subscribed to this forum. You will now receive site notifications when a topic is started.');

            case $request->has('topic_id') === true:
                abort_unless(
                    Topic::query()
                        ->where('id', '=', $request->topic_id)
                        ->whereRelation('forumPermissions', [
                            ['read_topic', '=', 1],
                            ['group_id', '=', $user->group_id],
                        ])
                        ->exists(),
                    403
                );

                $request->user()->subscribedTopics()->attach($request->topic_id);

                return back()
                    ->withSuccess('You are now subscribed to this topic. You will now receive site notifications when a reply is left.');

            default:
                return back()->withErrors(['Failed to subscribe.']);
        }
    }

    /**
     * Destroy a subscription.
     */
    public function destroy(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        $subscription = Subscription::findOrFail($id);

        abort_unless($subscription->user_id === $user->id, 403);

        $subscription->delete();

        return back()->withSuccess('You are now unsubscribed.');
    }
}

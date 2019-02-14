<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\Forum;
use App\Topic;
use App\Subscription;
use Brian2694\Toastr\Toastr;

class SubscriptionController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * SubscriptionController Constructor.
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Subscribe To A Topic.
     *
     * @param \Illuminate\Http\Request $request
     * @param Topic $topic
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function subscribeTopic(Request $request, string $route, Topic $topic)
    {
        if ($route == 'subscriptions') {
            $logger = 'forum_subscriptions';
            $params = [];
        }
        if (! isset($logger)) {
            $logger = 'forum_topic';
            $params = ['slug' => $topic->slug, 'id' => $topic->id];
        }

        if (! $request->user()->isSubscribed('topic', $topic->id)) {
            $subscription = new Subscription();
            $subscription->user_id = $request->user()->id;
            $subscription->topic_id = $topic->id;
            $subscription->save();

            return redirect()->route($logger, $params)
                ->with($this->toastr->success('You are now subscribed to topic, '.$topic->name.'. You will now receive site notifications when a reply is left.', 'Yay!', ['options']));
        } else {
            return redirect()->route($logger, $params)
                ->with($this->toastr->error('You are already subscribed to this topic', 'Whoops!', ['options']));
        }
    }

    /**
     * Unsubscribe To A Topic.
     *
     * @param \Illuminate\Http\Request $request
     * @param Topic $topic
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function unsubscribeTopic(Request $request, string $route, Topic $topic)
    {
        if ($route == 'subscriptions') {
            $logger = 'forum_subscriptions';
            $params = [];
        }
        if (! isset($logger)) {
            $logger = 'forum_topic';
            $params = ['slug' => $topic->slug, 'id' => $topic->id];
        }

        if ($request->user()->isSubscribed('topic', $topic->id)) {
            $subscription = $request->user()->subscriptions()->where('topic_id', '=', $topic->id)->first();
            $subscription->delete();

            return redirect()->route($logger, $params)
                ->with($this->toastr->info('You are no longer subscribed to topic, '.$topic->name.'. You will no longer receive site notifications when a reply is left.', 'Yay!', ['options']));
        } else {
            return redirect()->route($logger, $params)
                ->with($this->toastr->error('You are not subscribed this topic to begin with...', 'Whoops!', ['options']));
        }
    }

    /**
     * Subscribe To A Forum.
     *
     * @param \Illuminate\Http\Request $request
     * @param Forum $forum
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function subscribeForum(Request $request, string $route, Forum $forum)
    {
        if ($route == 'subscriptions') {
            $logger = 'forum_subscriptions';
            $params = [];
        }
        if (! isset($logger)) {
            $logger = 'forum_display';
            $params = ['slug' => $forum->slug, 'id' => $forum->id];
        }

        if (! $request->user()->isSubscribed('forum', $forum->id)) {
            $subscription = new Subscription();
            $subscription->user_id = $request->user()->id;
            $subscription->forum_id = $forum->id;
            $subscription->save();

            return redirect()->route($logger, $params)
                ->with($this->toastr->success('You are now subscribed to forum, '.$forum->name.'. You will now receive site notifications when a topic is started.', 'Yay!', ['options']));
        } else {
            return redirect()->route($logger, $params)
                ->with($this->toastr->error('You are already subscribed to this forum', 'Whoops!', ['options']));
        }
    }

    /**
     * Unsubscribe To A Forum.
     *
     * @param \Illuminate\Http\Request $request
     * @param Forum $forum
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function unsubscribeForum(Request $request, string $route, Forum $forum)
    {
        if ($route == 'subscriptions') {
            $logger = 'forum_subscriptions';
            $params = [];
        }
        if (! isset($logger)) {
            $logger = 'forum_display';
            $params = ['slug' => $forum->slug, 'id' => $forum->id];
        }

        if ($request->user()->isSubscribed('forum', $forum->id)) {
            $subscription = $request->user()->subscriptions()->where('forum_id', '=', $forum->id)->first();
            $subscription->delete();

            return redirect()->route($logger, $params)
                ->with($this->toastr->info('You are no longer subscribed to forum, '.$forum->name.'. You will no longer receive site notifications when a topic is started.', 'Yay!', ['options']));
        } else {
            return redirect()->route($logger, $params)
                ->with($this->toastr->error('You are not subscribed this forum to begin with...', 'Whoops!', ['options']));
        }
    }
}

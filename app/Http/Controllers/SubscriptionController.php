<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\Topic;
use App\TopicSubscription;
use \Toastr;

class SubscriptionController extends Controller
{
    /**
     * Subscribe To A Topic
     *
     * @param Topic $topic
     * @return Illuminate\Http\RedirectResponse
     */
    public function subscribe(Topic $topic)
    {
        if (!auth()->user()->isSubscribed($topic->id)) {

            $subscription = new TopicSubscription();
            $subscription->user_id = auth()->user()->id;
            $subscription->topic_id = $topic->id;
            $subscription->save();

            return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])
                ->with(Toastr::success('You are now subscribed to topic, ' . $topic->name . '. You will now receive site notifications when a reply is left.', 'Yay!', ['options']));
        } else {
            return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])
                ->with(Toastr::error('You are already subscribed to this topic', 'Whoops!', ['options']));
        }
    }

    /**
     * Unsubscribe To A Topic
     *
     * @param Topic $topic
     * @return Illuminate\Http\RedirectResponse
     */
    public function unsubscribe(Topic $topic)
    {
        if (auth()->user()->isSubscribed($topic->id)) {

            $subscription = auth()->user()->subscriptions()->where('topic_id', $topic->id)->first();
            $subscription->delete();

            return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])
                ->with(Toastr::info('You are no longer subscribed to topic, ' . $topic->name. '. You will no longer receive site notifications when a reply is left.', 'Yay!', ['options']));
        } else {
            return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])
                ->with(Toastr::error('You are not subscribed this topic to begin with...', 'Whoops!', ['options']));
        }
    }
}

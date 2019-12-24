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

use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

final class TopicLabelController extends Controller
{
    /**
     * @var \Illuminate\Routing\Redirector
     */
    private $redirector;

    public function __construct(Redirector $redirector)
    {
        $this->redirector = $redirector;
    }

    /**
     * Apply/Remove Approved Label.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve($id): RedirectResponse
    {
        $topic = Topic::findOrFail($id);
        $topic->approved = $topic->approved == 0 ? '1' : '0';
        $topic->save();

        return $this->redirector->route('forum_topic', ['id' => $topic->id])
            ->withInfo('Label Change Has Been Applied');
    }

    /**
     * Apply/Remove Denied Label.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deny($id): RedirectResponse
    {
        $topic = Topic::findOrFail($id);
        $topic->denied = $topic->denied == 0 ? '1' : '0';
        $topic->save();

        return $this->redirector->route('forum_topic', ['id' => $topic->id])
            ->withInfo('Label Change Has Been Applied');
    }

    /**
     * Apply/Remove Solved Label.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function solve($id): RedirectResponse
    {
        $topic = Topic::findOrFail($id);
        $topic->solved = $topic->solved == 0 ? '1' : '0';
        $topic->save();

        return $this->redirector->route('forum_topic', ['id' => $topic->id])
            ->withInfo('Label Change Has Been Applied');
    }

    /**
     * Apply/Remove Invalid Label.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function invalid($id): RedirectResponse
    {
        $topic = Topic::findOrFail($id);
        $topic->invalid = $topic->invalid == 0 ? '1' : '0';
        $topic->save();

        return $this->redirector->route('forum_topic', ['id' => $topic->id])
            ->withInfo('Label Change Has Been Applied');
    }

    /**
     * Apply/Remove Bug Label.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bug($id): RedirectResponse
    {
        $topic = Topic::findOrFail($id);
        $topic->bug = $topic->bug == 0 ? '1' : '0';
        $topic->save();

        return $this->redirector->route('forum_topic', ['id' => $topic->id])
            ->withInfo('Label Change Has Been Applied');
    }

    /**
     * Apply/Remove Suggestion Label.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function suggest($id): RedirectResponse
    {
        $topic = Topic::findOrFail($id);
        $topic->suggestion = $topic->suggestion == 0 ? '1' : '0';
        $topic->save();

        return $this->redirector->route('forum_topic', ['id' => $topic->id])
            ->withInfo('Label Change Has Been Applied');
    }

    /**
     * Apply/Remove Implemented Label.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function implement($id): RedirectResponse
    {
        $topic = Topic::findOrFail($id);
        $topic->implemented = $topic->implemented == 0 ? '1' : '0';
        $topic->save();

        return $this->redirector->route('forum_topic', ['id' => $topic->id])
            ->withInfo('Label Change Has Been Applied');
    }
}

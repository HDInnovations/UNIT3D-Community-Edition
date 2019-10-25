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
use Illuminate\Http\Request;

class TopicLabelController extends Controller
{
    /**
     * Forum Tag System.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function approvedTopic($id)
    {
        $topic = Topic::findOrFail($id);
        if ($topic->approved == 0) {
            $topic->approved = '1';
        } else {
            $topic->approved = '0';
        }
        $topic->save();

        return redirect()->route('forum_topic', ['id' => $topic->id])
            ->withInfo('Label Change Has Been Applied');
    }

    public function deniedTopic($id)
    {
        $topic = Topic::findOrFail($id);
        if ($topic->denied == 0) {
            $topic->denied = '1';
        } else {
            $topic->denied = '0';
        }
        $topic->save();

        return redirect()->route('forum_topic', ['id' => $topic->id])
            ->withInfo('Label Change Has Been Applied');
    }

    public function solvedTopic($id)
    {
        $topic = Topic::findOrFail($id);
        if ($topic->solved == 0) {
            $topic->solved = '1';
        } else {
            $topic->solved = '0';
        }
        $topic->save();

        return redirect()->route('forum_topic', ['id' => $topic->id])
            ->withInfo('Label Change Has Been Applied');
    }

    public function invalidTopic($id)
    {
        $topic = Topic::findOrFail($id);
        if ($topic->invalid == 0) {
            $topic->invalid = '1';
        } else {
            $topic->invalid = '0';
        }
        $topic->save();

        return redirect()->route('forum_topic', ['id' => $topic->id])
            ->withInfo('Label Change Has Been Applied');
    }

    public function bugTopic($id)
    {
        $topic = Topic::findOrFail($id);
        if ($topic->bug == 0) {
            $topic->bug = '1';
        } else {
            $topic->bug = '0';
        }
        $topic->save();

        return redirect()->route('forum_topic', ['id' => $topic->id])
            ->withInfo('Label Change Has Been Applied');
    }

    public function suggestionTopic($id)
    {
        $topic = Topic::findOrFail($id);
        if ($topic->suggestion == 0) {
            $topic->suggestion = '1';
        } else {
            $topic->suggestion = '0';
        }
        $topic->save();

        return redirect()->route('forum_topic', ['id' => $topic->id])
            ->withInfo('Label Change Has Been Applied');
    }

    public function implementedTopic($id)
    {
        $topic = Topic::findOrFail($id);
        if ($topic->implemented == 0) {
            $topic->implemented = '1';
        } else {
            $topic->implemented = '0';
        }
        $topic->save();

        return redirect()->route('forum_topic', ['id' => $topic->id])
            ->withInfo('Label Change Has Been Applied');
    }
}
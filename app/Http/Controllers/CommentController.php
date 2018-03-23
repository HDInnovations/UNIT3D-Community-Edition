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

use Illuminate\Http\Request;
use App\User;
use App\Article;
use App\Comment;
use App\Torrent;
use App\TorrentRequest;
use App\Shoutbox;
use App\PrivateMessage;
use App\Achievements\UserMadeComment;
use App\Achievements\UserMadeTenComments;
use App\Achievements\UserMade50Comments;
use App\Achievements\UserMade100Comments;
use App\Achievements\UserMade200Comments;
use App\Achievements\UserMade300Comments;
use App\Achievements\UserMade400Comments;
use App\Achievements\UserMade500Comments;
use App\Achievements\UserMade600Comments;
use App\Achievements\UserMade700Comments;
use App\Achievements\UserMade800Comments;
use App\Achievements\UserMade900Comments;
use App\Notifications\NewTorrentComment;
use App\Notifications\NewRequestComment;
use \Toastr;

class CommentController extends Controller
{

    /**
     * Add a comment on a artical
     *
     * @param $slug
     * @param $id
     *
     */
    public function article(Request $request, $slug, $id)
    {
        $article = Article::findOrFail($id);
        $user = auth()->user();

        // User's comment rights disbabled?
        if ($user->can_comment == 0) {
            return redirect()->route('article', ['slug' => $article->slug, 'id' => $article->id])->with(Toastr::error('Your Comment Rights Have Benn Revoked!!!', 'Whoops!', ['options']));
        }

        $comment = new Comment();
        $comment->content = $request->input('content');
        $comment->user_id = $user->id;
        $comment->article_id = $article->id;
        $v = validator($comment->toArray(), ['content' => 'required', 'user_id' => 'required', 'article_id' => 'required']);
        $appurl = config('app.url');
        if ($v->passes()) {
            Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "User [url={$appurl}/" . $user->username . "." . $user->id . "]" . $user->username . "[/url] has left a comment on article [url={$appurl}/articles/" . $article->slug . "." . $article->id . "]" . $article->title . "[/url]"]);
            cache()->forget('shoutbox_messages');
            $comment->save();
            Toastr::success('Your Comment Has Been Added!', 'Yay!', ['options']);
        } else {
            Toastr::error('A Error Has Occured And Your Comment Was Not Posted!', 'Whoops!', ['options']);
        }
        return redirect()->route('article', ['slug' => $article->slug, 'id' => $article->id]);
    }

    /**
     * Add a comment on a torrent
     *
     * @param $slug
     * @param $id
     */
    public function torrent(Request $request, $slug, $id)
    {
        $torrent = Torrent::findOrFail($id);
        $user = auth()->user();

        // User's comment rights disbabled?
        if ($user->can_comment == 0) {
            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::error('Your Comment Rights Have Benn Revoked!!!', 'Whoops!', ['options']));
        }

        $comment = new Comment();
        $comment->content = $request->input('content');
        $comment->anon = $request->input('anonymous');
        $comment->user_id = $user->id;
        $comment->torrent_id = $torrent->id;
        $v = validator($comment->toArray(), ['content' => 'required', 'user_id' => 'required', 'torrent_id' => 'required', 'anon' => 'required']);
        if ($v->passes()) {
            $comment->save();
            Toastr::success('Your Comment Has Been Added!', 'Yay!', ['options']);

            // Achievements
            $user->unlock(new UserMadeComment(), 1);
            $user->addProgress(new UserMadeTenComments(), 1);
            $user->addProgress(new UserMade50Comments(), 1);
            $user->addProgress(new UserMade100Comments(), 1);
            $user->addProgress(new UserMade200Comments(), 1);
            $user->addProgress(new UserMade300Comments(), 1);
            $user->addProgress(new UserMade400Comments(), 1);
            $user->addProgress(new UserMade500Comments(), 1);
            $user->addProgress(new UserMade600Comments(), 1);
            $user->addProgress(new UserMade700Comments(), 1);
            $user->addProgress(new UserMade800Comments(), 1);
            $user->addProgress(new UserMade900Comments(), 1);

            //Notification
            if ($user->id != $torrent->user_id) {
                User::find($torrent->user_id)->notify(new NewTorrentComment($comment));
            }

            // Auto Shout
            $appurl = config('app.url');
            if ($comment->anon == 0) {
                Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "User [url={$appurl}/" . $user->username . "." . $user->id . "]" . $user->username . "[/url] has left a comment on Torrent [url={$appurl}/torrents/" . $torrent->slug . "." . $torrent->id . "]" . $torrent->name . "[/url]"]);
                cache()->forget('shoutbox_messages');
            } else {
                Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "An anonymous user has left a comment on torrent [url={$appurl}/torrents/" . $torrent->slug . "." . $torrent->id . "]" . $torrent->name . "[/url]"]);
                cache()->forget('shoutbox_messages');
            }
        } else {
            Toastr::error('A Error Has Occured And Your Comment Was Not Posted!', 'Sorry', ['options']);
        }
        return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id]);
    }

    /**
     * Add a comment on a request
     *
     * @param $slug
     * @param $id
     */
    public function request(Request $request, $id)
    {
        $torrentRequest = TorrentRequest::findOrFail($id);
        $user = auth()->user();

        // User's comment rights disbabled?
        if ($user->can_comment == 0) {
            return redirect()->route('request', ['id' => $request->id])->with(Toastr::error('Your Comment Rights Have Benn Revoked!!!', 'Whoops!', ['options']));
        }

        $comment = new Comment();
        $comment->content = $request->input('content');
        $comment->anon = $request->input('anonymous');
        $comment->user_id = $user->id;
        $comment->requests_id = $torrentRequest->id;
        $v = validator($comment->toArray(), ['content' => 'required', 'user_id' => 'required', 'requests_id' => 'required']);
        if ($v->passes()) {
            $comment->save();
            Toastr::success('Your Comment Has Been Added!', 'Yay!', ['options']);

            // Achievements
            $user->unlock(new UserMadeComment(), 1);
            $user->addProgress(new UserMadeTenComments(), 1);
            $user->addProgress(new UserMade50Comments(), 1);
            $user->addProgress(new UserMade100Comments(), 1);
            $user->addProgress(new UserMade200Comments(), 1);
            $user->addProgress(new UserMade300Comments(), 1);
            $user->addProgress(new UserMade400Comments(), 1);
            $user->addProgress(new UserMade500Comments(), 1);
            $user->addProgress(new UserMade600Comments(), 1);
            $user->addProgress(new UserMade700Comments(), 1);
            $user->addProgress(new UserMade800Comments(), 1);
            $user->addProgress(new UserMade900Comments(), 1);

            $appurl = config('app.url');

            // Auto PM
            if ($user->id != $request->user_id) {
                PrivateMessage::create(['sender_id' => "1", 'reciever_id' => $torrentRequest->user_id, 'subject' => "Your Request " . $torrentRequest->name . " Has A New Comment!", 'message' => $comment->user->username . " Has Left A Comment On [url={$appurl}/request/" . $torrentRequest->id . "]" . $torrentRequest->name . "[/url]"]);
            }

            // Auto Shout
            if ($comment->anon == 0) {
                Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "User [url={$appurl}/" . $user->username . "." . $user->id . "]" . $user->username . "[/url] has left a comment on Request [url={$appurl}/request/" . $torrentRequest->id . "]" . $torrentRequest->name . "[/url]"]);
                cache()->forget('shoutbox_messages');
            } else {
                Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "An anonymous user has left a comment on request [url={$appurl}/request/" . $torrentRequest->id . "]" . $torrentRequest->name . "[/url]"]);
                cache()->forget('shoutbox_messages');
            }
        } else {
            Toastr::error('A Error Has Occured And Your Comment Was Not Posted!', 'Sorry', ['options']);
        }
        return redirect()->route('request', ['id' => $torrentRequest->id]);
    }

    /**
     * Add a comment on a torrent via quickthanks
     *
     * @param $slug
     * @param $id
     */
    public function quickthanks($id)
    {
        $torrent = Torrent::findOrFail($id);
        $user = auth()->user();
        $uploader = $torrent->user;

        // User's comment rights disbabled?
        if ($user->can_comment == 0) {
            return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id])->with(Toastr::error('Your Comment Rights Have Benn Revoked!!!', 'Whoops!', ['options']));
        }

        $comment = new Comment();
        $thankArray = ["Thanks for the upload! :thumbsup_tone2:", "Time and effort is much appreciated :thumbsup_tone2:", "Great upload! :fire:", "Thankyou :smiley:"];
        $selected = mt_rand(0, count($thankArray) - 1);
        $comment->content = $thankArray[$selected];
        $comment->user_id = $user->id;
        $comment->torrent_id = $torrent->id;
        $v = validator($comment->toArray(), ['content' => 'required', 'user_id' => 'required', 'torrent_id' => 'required']);
        if ($v->passes()) {
            $comment->save();
            Toastr::success('Your Comment Has Been Added!', 'Yay!', ['options']);

            // Achievements
            $user->unlock(new UserMadeComment(), 1);
            $user->addProgress(new UserMadeTenComments(), 1);
            $user->addProgress(new UserMade50Comments(), 1);
            $user->addProgress(new UserMade100Comments(), 1);
            $user->addProgress(new UserMade200Comments(), 1);
            $user->addProgress(new UserMade300Comments(), 1);
            $user->addProgress(new UserMade400Comments(), 1);
            $user->addProgress(new UserMade500Comments(), 1);
            $user->addProgress(new UserMade600Comments(), 1);
            $user->addProgress(new UserMade700Comments(), 1);
            $user->addProgress(new UserMade800Comments(), 1);
            $user->addProgress(new UserMade900Comments(), 1);

            //Notification
            if ($user->id != $torrent->user_id) {
                User::find($torrent->user_id)->notify(new NewTorrentComment($comment));
            }

            // Auto Shout
            $appurl = config('app.url');
            Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "User [url={$appurl}/" . $user->username . "." . $user->id . "]" . $user->username . "[/url] has left a comment on Torrent [url={$appurl}/torrents/" . $torrent->slug . "." . $torrent->id . "]" . $torrent->name . "[/url]"]);
            cache()->forget('shoutbox_messages');
        } else {
            Toastr::error('A Error Has Occured And Your Comment Was Not Posted!', 'Whoops!', ['options']);
        }

        return redirect()->route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id]);
    }

    /**
     * Edit a comment
     *
     *
     * @param $comment_id
     */
    public function editComment(Request $request, $comment_id)
    {
        $user = auth()->user();
        $comment = Comment::findOrFail($comment_id);

        if ($user->group->is_modo || $user->id == $comment->user_id) {
            $content = $request->input('comment-edit');
            $comment->content = $content;
            $comment->save();

            return back()->with(Toastr::success('Comment Has Been Edited.', 'Yay!', ['options']));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Delete a comment on a torrent
     *
     *
     * @param $comment_id
     */
    public function deleteComment($comment_id)
    {
        $user = auth()->user();
        $comment = Comment::findOrFail($comment_id);

        if ($user->group->is_modo || $user->id == $comment->user_id) {
            $comment->delete();

            return back()->with(Toastr::success('Comment Has Been Deleted.', 'Yay!', ['options']));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}

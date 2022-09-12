<?php

use App\Models\Comment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->morphs('commentable');
            $table->foreignId('parent_id')->after('user_id')->nullable()->constrained('comments')->onDelete('cascade');
        });

        $comments = Comment::all();
        foreach ($comments as $comment) {
            if ($comment->torrent_id !== null) {
                $comment->commentable_id = $comment->torrent_id;
                $comment->commentable_type = 'App\Models\Torrent';
                $comment->save();
            }

            if ($comment->article_id !== null) {
                $comment->commentable_id = $comment->article_id;
                $comment->commentable_type = 'App\Models\Article';
                $comment->save();
            }

            if ($comment->requests_id !== null) {
                $comment->commentable_id = $comment->requests_id;
                $comment->commentable_type = 'App\Models\TorrentRequest';
                $comment->save();
            }

            if ($comment->collection_id !== null) {
                $comment->commentable_id = $comment->collection_id;
                $comment->commentable_type = 'App\Models\Collection';
                $comment->save();
            }

            if ($comment->playlist_id !== null) {
                $comment->commentable_id = $comment->playlist_id;
                $comment->commentable_type = 'App\Models\Playlist';
                $comment->save();
            }

            if ($comment->ticket_id !== null) {
                $comment->commentable_id = $comment->ticket_id;
                $comment->commentable_type = 'App\Models\Ticket';
                $comment->save();
            }
        }

        Schema::table('comments', function (Blueprint $table) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $table->dropForeign('fk_comments_articles_1');
            $table->dropIndex('fk_comments_torrents_1');
            $table->dropIndex('comments_playlist_id_index');
            $table->dropIndex('comments_collection_id_index');
            $table->dropIndex('comments_ticket_id_index');
            $table->dropColumn('torrent_id', 'article_id', 'requests_id', 'collection_id', 'playlist_id', 'ticket_id');
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTopicSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('topic_subscriptions', 'subscriptions');
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropUnique('topic_subscriptions_user_id_topic_id_unique');
            $table->integer('forum_id')->nullable()->index()->after('user_id');
            $table->integer('user_id')->change();
            $table->integer('topic_id')->nullable()->change();
            $table->index('user_id');
            $table->index('topic_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('topic_subscriptions', function (Blueprint $table) {
            //
        });
    }
}

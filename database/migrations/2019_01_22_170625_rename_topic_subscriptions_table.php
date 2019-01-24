<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTopicSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('topic_subscriptions', function (Blueprint $table) {
            Schema::rename('topic_subscriptions', 'subscriptions');
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
            Schema::rename('subscriptions', 'topic_subscriptions');
        });
    }
}

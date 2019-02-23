<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRequestToUserPrivacyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_privacy', function (Blueprint $table) {
            $table->boolean('show_profile_request_extra')->index()->default(1)->after('show_profile_forum_extra');
            $table->json('json_request_groups')->after('json_rank_groups');
            $table->json('json_other_groups')->after('json_request_groups');
            $table->boolean('show_online')->index()->default(1)->after('show_follower');
            $table->boolean('show_peer')->index()->default(1)->after('show_online');
            $table->boolean('show_requested')->index()->default(1)->after('show_rank');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_privacy', function (Blueprint $table) {
            //
        });
    }
}

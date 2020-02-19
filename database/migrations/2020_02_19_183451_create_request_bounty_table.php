<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestBountyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('request_bounty', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->float('seedbonus', 12, 2)->default(0.00);
            $table->integer('requests_id');
            $table->boolean('anon')->default(0);
            $table->nullableTimestamps();
        });

        Schema::table('request_bounty', function (Blueprint $table) {
            $table->index('user_id', 'addedby');
            $table->index('requests_id', 'request_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('request_bounty');
    }

}
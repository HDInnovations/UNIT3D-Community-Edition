<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('request_claims', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('request_id');
            $table->string('username')->nullable();
            $table->smallInteger('anon')->default(0);
            $table->nullableTimestamps();
        });

        Schema::table('request_claims', function (Blueprint $table) {
            $table->index('username', 'user_id');
            $table->index('request_id', 'request_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('request_claims');
    }

}
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('category_id');
            $table->string('type');
            $table->string('imdb')->nullable();
            $table->string('tvdb')->nullable();
            $table->string('tmdb')->nullable();
            $table->string('mal')->nullable();
            $table->string('igdb')->default('0');
            $table->text('description');
            $table->integer('user_id');
            $table->float('bounty', 22, 2);
            $table->integer('votes')->default(0);
            $table->boolean('claimed')->nullable();
            $table->boolean('anon')->default(0);
            $table->nullableTimestamps();
            $table->integer('filled_by')->nullable();
            $table->string('filled_hash')->nullable();
            $table->dateTime('filled_when')->nullable();
            $table->boolean('filled_anon')->default(0);
            $table->integer('approved_by')->nullable();
            $table->dateTime('approved_when')->nullable();
        });

        Schema::table('requests', function (Blueprint $table) {
            $table->index('mal', 'mal');
            $table->index('filled_by', 'filled_by');
            $table->index('approved_by', 'approved_by');
            $table->index('category_id', 'category_id');
            $table->index('tvdb', 'tvdb');
            $table->index('tmdb', 'tmdb');
            $table->index('user_id', 'requests_user_id_foreign');
            $table->index('filled_hash', 'filled_hash');
            $table->index('igdb');
            $table->index('imdb', 'imdb');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
    }

}
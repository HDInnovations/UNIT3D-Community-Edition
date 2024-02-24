<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('topic_reads', function (Blueprint $table): void {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('topic_id')->index();
            $table->integer('last_read_post_id');

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('topic_id')->references('id')->on('topics')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('last_read_post_id')->references('id')->on('posts')->cascadeOnUpdate()->cascadeOnDelete();
            $table->primary(['user_id', 'topic_id']);
        });
    }
};

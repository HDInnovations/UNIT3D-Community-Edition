<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('private_messages', function (Blueprint $table): void {
            $table->dropForeign(['sender_id']);
            $table->dropIndex(['sender_id', 'read']);
            $table->index(['receiver_id', 'read']);
            $table->foreign('sender_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('notifications', function (Blueprint $table): void {
            $table->index(['notifiable_type', 'notifiable_id', 'read_at']);
        });

        Schema::table('warnings', function (Blueprint $table): void {
            $table->index(['user_id', 'active', 'deleted_at']);
        });

        Schema::table('genre_movie', function (Blueprint $table): void {
            $table->index('movie_id');
        });
    }
};

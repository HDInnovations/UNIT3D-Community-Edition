<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('torrent_trumps', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('torrent_id');
            $table->unsignedInteger('user_id');
            $table->text('reason');
            $table->unique(['torrent_id', 'user_id']);
            $table->timestamps();

            // Pointless since we use soft deletes, but it's good practice.
            $table->foreign('torrent_id')
                ->references('id')
                ->on('torrents')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
};

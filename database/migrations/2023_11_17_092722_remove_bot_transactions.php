<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        //        // Bot Transactions
        //
        //        Schema::table('bot_transactions', function (Blueprint $table): void {
        //            $table->drop();
        //        });
        //
        //        // Messages

        Schema::table('messages', function (Blueprint $table): void {
            //            $table->dropColumn('bot_id');
            $table->unsignedInteger('chatroom_id')->nullable()->change();
        });

        DB::table('messages')
            ->where('chatroom_id', '=', 0)
            ->update([
                'chatroom_id' => null,
            ]);

        DB::table('messages')
            ->whereNotIn('chatroom_id', DB::table('chatrooms')->select('id'))
            ->delete();

        // Users

        Schema::table('users', function (Blueprint $table): void {
            $table->unsignedInteger('chatroom_id')->nullable()->change();
        });

        DB::table('users')
            ->whereNotIn('chatroom_id', DB::table('chatrooms')->select('id'))
            ->update([
                'chatroom_id' => null,
            ]);

        Schema::table('users', function (Blueprint $table): void {
            $table->unsignedInteger('chatroom_id')->nullable()->change();

            $table->foreign('chatroom_id')->references('id')->on('chatrooms')->nullOnDelete();
        });

        //        // Echoes
        //
        //        DB::table('user_echoes')
        //            ->whereNotNull('bot_id')
        //            ->update([
        //                'target_id' => 1,
        //            ]);
        //
        //        Schema::table('user_echoes', function (Blueprint $table): void {
        //            $table->dropColumn('bot_id');
        //        });
        //
        //        // Audibles
        //
        //        DB::table('user_audibles')
        //            ->whereNotNull('bot_id')
        //            ->update([
        //                'target_id' => 1,
        //            ]);
        //
        //        Schema::table('user_audibles', function (Blueprint $table): void {
        //            $table->dropColumn('bot_id');
        //        });
        //
        //        // Bots
        //
        //        // Delete Casino Bot, Bet Bot and Trivia Bot respectively
        //        DB::table('bots')->whereIn('id', [3,4,5])->delete();
        //
        //        Schema::table('bots', function (Blueprint $table): void {
        //            $table->dropColumn([
        //                'info',
        //                'about',
        //                'is_triviabot',
        //                'is_casinobot',
        //                'is_betbot',
        //                'uploaded',
        //                'downloaded',
        //                'fl_tokens',
        //                'seedbonus',
        //                'invites',
        //            ]);
        //        });
    }
};

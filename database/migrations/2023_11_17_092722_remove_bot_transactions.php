<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('bot_transactions', function (Blueprint $table): void {
            $table->drop();
        });

        Schema::table('messages', function (Blueprint $table): void {
            $table->dropColumn('bot_id');
        });

        DB::table('user_echoes')
            ->whereNotNull('bot_id')
            ->update([
                'target_id' => 1,
            ]);

        DB::table('user_audibles')
            ->whereNotNull('bot_id')
            ->update([
                'target_id' => 1,
            ]);

        Schema::table('user_echoes', function (Blueprint $table): void {
            $table->dropColumn('bot_id');
        });

        Schema::table('user_audibles', function (Blueprint $table): void {
            $table->dropColumn('bot_id');
        });

        // Delete Casino Bot
        DB::table('bots')->whereIn('id', [3,4,5])->delete();

        Schema::table('bots', function (Blueprint $table): void {
            $table->dropColumn([
                'info',
                'about',
                'is_triviabot',
                'is_casinobot',
                'is_betbot',
                'uploaded',
                'downloaded',
                'fl_tokens',
                'seedbonus',
                'invites',
            ]);
        });
    }
};

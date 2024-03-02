<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('post_tips', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('sender_id')->nullable();
            $table->unsignedInteger('recipient_id')->nullable();
            $table->integer('post_id')->nullable();
            $table->decimal('bon', 22, 2);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('sender_id')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('recipient_id')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('post_id')->references('id')->on('posts')->cascadeOnUpdate()->nullOnDelete();
        });

        DB::table('post_tips')->insertUsing(
            ['id', 'bon', 'sender_id', 'recipient_id', 'post_id', 'created_at'],
            DB::table('bon_transactions')
                ->select([
                    'id',
                    'cost',
                    'sender_id',
                    'receiver_id',
                    DB::raw('IF(EXISTS(SELECT * FROM posts WHERE id = post_id), post_id, null)'),
                    'created_at',
                ])
                ->where('name', '=', 'tip')
                ->whereNotNull('post_id')
        );

        DB::table('bon_transactions')
            ->where('name', '=', 'tip')
            ->whereNotNull('post_id')
            ->delete();

        Schema::table('bon_transactions', function (Blueprint $table): void {
            $table->dropColumn('post_id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('gifts', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('sender_id')->nullable();
            $table->unsignedInteger('recipient_id')->nullable();
            $table->decimal('bon', 22, 2);
            $table->text('message');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('sender_id')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('recipient_id')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
        });

        DB::table('gifts')->insertUsing(
            ['id', 'bon', 'sender_id', 'recipient_id', 'message', 'created_at'],
            DB::table('bon_transactions')
                ->select(['id', 'cost', 'sender_id', 'receiver_id', 'comment', 'created_at'])
                ->where('name', '=', 'gift')
        );

        DB::table('bon_transactions')->where('name', '=', 'gift')->delete();
    }
};

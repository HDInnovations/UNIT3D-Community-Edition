<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('passkeys', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('content');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        DB::table('users')
            ->lazyById()
            ->each(fn ($user) => DB::table('passkeys')->insert([
                'user_id'    => $user->id,
                'content'    => $user->passkey,
                'created_at' => now(),
            ]));
    }
};

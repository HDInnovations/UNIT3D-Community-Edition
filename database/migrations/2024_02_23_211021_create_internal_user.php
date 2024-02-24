<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('internal_user', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('internal_id');
            $table->unsignedInteger('position');
            $table->timestamps();

            $table->unique(['user_id', 'internal_id']);

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('internal_id')->references('id')->on('internals')->cascadeOnUpdate()->cascadeOnDelete();
        });

        DB::table('internal_user')->insertUsing(
            ['position', 'user_id', 'internal_id', 'created_at', 'updated_at'],
            DB::table('users')
                ->select([DB::raw('1'), 'id', 'internal_id', DB::raw('now()'), DB::raw('now()')])
                ->whereNotNull('internal_id')
                ->whereIn('internal_id', DB::table('internals')->select('id'))
        );

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('internal_id');
        });
    }
};

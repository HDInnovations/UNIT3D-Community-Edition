<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('request_claims', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->nullable()->after('request_id');
        });

        DB::table('request_claims')
            ->leftJoin('users', 'request_claims.username', '=', 'users.username')
            ->update([
                'user_id' => DB::raw('users.id'),
            ]);

        DB::table('request_claims')
            ->whereNull('user_id')
            ->delete();

        Schema::table('request_claims', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->nullable(false)->change();
            $table->dropColumn('username');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }
};

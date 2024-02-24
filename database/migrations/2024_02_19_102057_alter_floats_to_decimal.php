<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->decimal('seedbonus', 12, 2)->default(0)->change();
        });

        Schema::table('requests', function (Blueprint $table): void {
            $table->decimal('bounty', 12, 2)->change();
        });

        Schema::table('request_bounty', function (Blueprint $table): void {
            $table->decimal('seedbonus', 12, 2)->default(0)->change();
        });

        Schema::table('bots', function (Blueprint $table): void {
            $table->decimal('seedbonus', 12, 2)->default(0)->change();
        });

        Schema::table('bon_transactions', function (Blueprint $table): void {
            $table->decimal('cost', 22, 2)->default(0)->change();
        });

        DB::statement('ALTER TABLE users ADD CONSTRAINT check_users_seedbonus CHECK (seedbonus >= 0);');
        DB::statement('ALTER TABLE requests ADD CONSTRAINT check_requests_bounty CHECK (bounty >= 0);');
        DB::statement('ALTER TABLE request_bounty ADD CONSTRAINT check_request_bounty_seedbonus CHECK (seedbonus >= 0);');
        DB::statement('ALTER TABLE bots ADD CONSTRAINT check_bots_seedbonus CHECK (seedbonus >= 0);');
    }
};

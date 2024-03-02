<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        DB::table('bon_transactions')->where('name', '=', 'request')->delete();

        Schema::table('bon_transactions', function (Blueprint $table): void {
            $table->dropColumn('comment');
        });
    }
};

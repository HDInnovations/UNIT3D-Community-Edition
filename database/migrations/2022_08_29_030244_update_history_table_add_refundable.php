<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('history', function (Blueprint $table): void {
            $table->unsignedBigInteger('refunded_download')
                ->after('downloaded')
                ->default(0);
        });
    }
};

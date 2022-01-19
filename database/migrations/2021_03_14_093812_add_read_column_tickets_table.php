<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->tinyInteger('user_read')->nullable()->after('staff_id');
            $table->tinyInteger('staff_read')->nullable()->after('user_read');
        });
    }
};

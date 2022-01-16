<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetworkTvTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('network_tv', function (Blueprint $table) {
            $table->unsignedInteger('network_id');
            $table->unsignedInteger('tv_id');
            $table->primary(['network_id', 'tv_id']);
        });
    }
}

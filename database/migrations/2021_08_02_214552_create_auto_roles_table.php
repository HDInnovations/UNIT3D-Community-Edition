<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_roles', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->boolean('enabled')->default(false);
            $table->foreignId('role_id');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->enum('type', ['give', 'remove']);
            $table->boolean('buffer')->default(false);
            $table->float('bufferMin')->nullable();
            $table->float('bufferMax')->nullable();
            $table->boolean('download')->default(false);
            $table->float('downloadMin')->nullable();
            $table->float('downloadMax')->nullable();
            $table->boolean('upload')->default(false);
            $table->float('uploadMin')->nullable();
            $table->float('uploadMax')->nullable();
            $table->boolean('ratio')->default(false);
            $table->float('ratioMin')->nullable();
            $table->float('ratioMax')->nullable();
            $table->boolean('accountAge')->default(false);
            $table->float('accountAgeMin')->nullable();
            $table->float('accountAgeMax')->nullable();
            $table->boolean('leechingCount')->default(false);
            $table->float('leechingCountMin')->nullable();
            $table->float('leechingCountMax')->nullable();
            $table->boolean('seedingCount')->default(false);
            $table->float('seedingCountMin')->nullable();
            $table->float('seedingCountMax')->nullable();
            $table->boolean('uploadCount')->default(false);
            $table->float('uploadCountMin')->nullable();
            $table->float('uploadCountMax')->nullable();
            $table->boolean('downloadCount')->default(false);
            $table->float('downloadCountMin')->nullable();
            $table->float('downloadCountMax')->nullable();
            $table->boolean('requestCount')->default(false);
            $table->float('requestCountMin')->nullable();
            $table->float('requestCountMax')->nullable();
            $table->boolean('postCount')->default(false);
            $table->float('postCountMin')->nullable();
            $table->float('postCountMax')->nullable();
            $table->boolean('commentCount')->default(false);
            $table->float('commentCountMin')->nullable();
            $table->float('commentCountMax')->nullable();
            $table->boolean('inviteCount')->default(false);
            $table->float('inviteCountMin')->nullable();
            $table->float('inviteCountMax')->nullable();
            $table->boolean('inviteBalance')->default(false);
            $table->float('inviteBalanceMin')->nullable();
            $table->float('inviteBalanceMax')->nullable();
            $table->boolean('bonBalance')->default(false);
            $table->float('bonBalanceMin')->nullable();
            $table->float('bonBalanceMax')->nullable();
            $table->boolean('warningsBalance')->default(false);
            $table->float('warningsBalanceMin')->nullable();
            $table->float('warningsBalanceMax')->nullable();
            $table->boolean('downloadPurchase')->default(false);
            $table->float('downloadPurchaseMin')->nullable();
            $table->float('downloadPurchaseMax')->nullable();
            $table->boolean('uploadPurchase')->default(false);
            $table->float('uploadPurchaseMin')->nullable();
            $table->float('uploadPurchaseMax')->nullable();
            $table->timestamps();
        });
    }
};

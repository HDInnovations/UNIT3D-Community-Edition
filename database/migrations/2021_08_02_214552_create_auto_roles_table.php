<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
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
            $table->unsignedBigInteger('bufferMin')->nullable();
            $table->unsignedBigInteger('bufferMax')->nullable();
            $table->boolean('download')->default(false);
            $table->unsignedBigInteger('downloadMin')->nullable();
            $table->unsignedBigInteger('downloadMax')->nullable();
            $table->boolean('upload')->default(false);
            $table->unsignedBigInteger('uploadMin')->nullable();
            $table->unsignedBigInteger('uploadMax')->nullable();
            $table->boolean('ratio')->default(false);
            $table->float('ratioMin')->nullable();
            $table->float('ratioMax')->nullable();
            $table->boolean('accountAge')->default(false);
            $table->unsignedBigInteger('accountAgeMin')->nullable();
            $table->unsignedBigInteger('accountAgeMax')->nullable();
            $table->boolean('leechingCount')->default(false);
            $table->unsignedBigInteger('leechingCountMin')->nullable();
            $table->unsignedBigInteger('leechingCountMax')->nullable();
            $table->boolean('seedingCount')->default(false);
            $table->unsignedBigInteger('seedingCountMin')->nullable();
            $table->unsignedBigInteger('seedingCountMax')->nullable();
            $table->boolean('uploadCount')->default(false);
            $table->unsignedBigInteger('uploadCountMin')->nullable();
            $table->unsignedBigInteger('uploadCountMax')->nullable();
            $table->boolean('downloadCount')->default(false);
            $table->unsignedBigInteger('downloadCountMin')->nullable();
            $table->unsignedBigInteger('downloadCountMax')->nullable();
            $table->boolean('requestCount')->default(false);
            $table->unsignedBigInteger('requestCountMin')->nullable();
            $table->unsignedBigInteger('requestCountMax')->nullable();
            $table->boolean('postCount')->default(false);
            $table->unsignedBigInteger('postCountMin')->nullable();
            $table->unsignedBigInteger('postCountMax')->nullable();
            $table->boolean('commentCount')->default(false);
            $table->unsignedBigInteger('commentCountMin')->nullable();
            $table->unsignedBigInteger('commentCountMax')->nullable();
            $table->boolean('inviteCount')->default(false);
            $table->unsignedBigInteger('inviteCountMin')->nullable();
            $table->unsignedBigInteger('inviteCountMax')->nullable();
            $table->boolean('inviteBalance')->default(false);
            $table->unsignedBigInteger('inviteBalanceMin')->nullable();
            $table->unsignedBigInteger('inviteBalanceMax')->nullable();
            $table->boolean('bonBalance')->default(false);
            $table->unsignedBigInteger('bonBalanceMin')->nullable();
            $table->unsignedBigInteger('bonBalanceMax')->nullable();
            $table->boolean('warningsBalance')->default(false);
            $table->unsignedBigInteger('warningsBalanceMin')->nullable();
            $table->unsignedBigInteger('warningsBalanceMax')->nullable();
            $table->boolean('downloadPurchase')->default(false);
            $table->unsignedBigInteger('downloadPurchaseMin')->nullable();
            $table->unsignedBigInteger('downloadPurchaseMax')->nullable();
            $table->boolean('uploadPurchase')->default(false);
            $table->unsignedBigInteger('uploadPurchaseMin')->nullable();
            $table->unsignedBigInteger('uploadPurchaseMax')->nullable();
            $table->timestamps();
        });
    }
};

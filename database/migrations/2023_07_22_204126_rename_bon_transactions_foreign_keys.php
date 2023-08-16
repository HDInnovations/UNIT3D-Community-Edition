<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('bon_transactions', function (Blueprint $table): void {
            $table->renameColumn('itemID', 'bon_exchange_id');
            $table->renameColumn('sender', 'sender_id');
            $table->renameColumn('receiver', 'receiver_id');
            $table->renameColumn('date_actioned', 'created_at');
        });
    }
};

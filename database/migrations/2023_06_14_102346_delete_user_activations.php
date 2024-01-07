<?php

use App\Enums\UserGroup;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::drop('user_activations');

        Schema::table('users', function (Blueprint $table): void {
            $table->timestamp('email_verified_at')->nullable();
        });

        $users = User::where('group_id', '!=', UserGroup::VALIDATING->value)->get();

        foreach ($users as $user) {
            $user->markEmailAsVerified();
        }
    }
};

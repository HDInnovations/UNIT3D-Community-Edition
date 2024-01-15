<?php

use App\Models\Group;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('groups', function (Blueprint $table): void {
            $table->boolean('is_editor')->after('is_internal')->default(false);
            $table->index(['is_editor']);
        });

        Group::updateOrCreate(
            ['slug' => 'editor'],
            [
                'name'         => 'Editor',
                'slug'         => 'editor',
                'position'     => 17,
                'color'        => '#15B097',
                'icon'         => config('other.font-awesome').' fa-user-pen',
                'effect'       => 'none',
                'autogroup'    => 0,
                'is_owner'     => 0,
                'is_admin'     => 0,
                'is_modo'      => 0,
                'is_editor'    => 1,
                'is_internal'  => 0,
                'is_trusted'   => 1,
                'is_freeleech' => 1,
                'is_immune'    => 1,
                'can_upload'   => 0,
                'level'        => 0,
            ]
        );
    }
};

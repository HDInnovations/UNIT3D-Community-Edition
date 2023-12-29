<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::rename('bon_exchange', 'bon_exchanges');
        Schema::rename('collection', 'collections');
        Schema::rename('movie', 'movies');
        Schema::rename('person', 'people');
        Schema::rename('personal_freeleech', 'personal_freeleeches');
        Schema::rename('clients', 'seedboxes');
    }
};

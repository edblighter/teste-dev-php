<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique();       // ISO 3166-1 alpha-3
            $table->string('name');                    // Country name
            $table->string('iso_3166_2', 2)->nullable(); // ISO 3166-1 alpha-2
            $table->string('iso_3166_3', 3)->nullable(); // ISO 3166-1 alpha-3 (again)
            $table->string('numeric_code', 3)->nullable(); // ISO numeric
            $table->string('tld', 5)->nullable();       // Top-level domain
            $table->string('phonecode', 10)->nullable();
            $table->string('capital', 100)->nullable();
            $table->string('currency', 10)->nullable();
            $table->string('currency_name', 50)->nullable();
            $table->string('currency_symbol', 10)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('subregion', 100)->nullable();
            $table->string('emoji', 5)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};

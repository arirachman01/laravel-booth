<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->string('eyebrow');
            $table->string('website_name');
            $table->string('tagline');

            $table->text('website_description');

            $table->integer('photo_price');
            $table->integer('photo_count');
            $table->integer('countdown');
            $table->integer('max_time');

            $table->string('primary_color');
            $table->string('secondary_color');
            $table->string('background_color');
            $table->string('surface_color');
            $table->string('text_color');
            $table->string('accent_color');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
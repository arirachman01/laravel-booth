<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photo_results', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->text('url');
            $table->unsignedBigInteger('session_id')->nullable();

            $table->foreign('session_id')
                ->references('id')
                ->on('photo_sessions')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photo_results');
    }
};

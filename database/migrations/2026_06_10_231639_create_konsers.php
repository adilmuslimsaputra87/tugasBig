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
        Schema::create('konsers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('artists_id')->nullable()->constrained('artists')->nullOnDelete();
            $table->string('genre')->nullable();
            $table->dateTime('date');
            $table->time('time')->default('19:00');
            $table->string('venue');
            $table->string('city');
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->string('trailer')->nullable();
            $table->unsignedBigInteger('price')->default(0);
            $table->unsignedInteger('capacity')->default(1000);
            $table->enum('status', ['draft', 'published', 'sold_out', 'cancelled'])->default('draft');
            $table->enum('type', ['lokal', 'internasional'])->default('lokal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konsers');
    }
};

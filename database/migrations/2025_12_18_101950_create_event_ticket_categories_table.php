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
        Schema::create('event_ticket_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('category_name'); // VIP, Regular, Early Bird, dll
            $table->decimal('price', 15, 2); // Harga tiket kategori ini
            $table->integer('stock')->nullable(); // Jumlah tiket tersedia (opsional)
            $table->integer('sold')->default(0); // Jumlah terjual
            $table->text('description')->nullable(); // Deskripsi kategori
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_ticket_categories');
    }
};

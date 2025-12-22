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
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('attendee_name')->nullable()->after('ticket_code');
            $table->string('attendee_email')->nullable()->after('attendee_name');
            $table->string('attendee_phone')->nullable()->after('attendee_email');
            $table->string('attendee_id_number')->nullable()->after('attendee_phone');
            $table->decimal('price', 12, 2)->default(0)->after('attendee_id_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['attendee_name', 'attendee_email', 'attendee_phone', 'attendee_id_number', 'price']);
        });
    }
};

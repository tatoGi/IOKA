<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rental_resale', function (Blueprint $table) {
            // ...existing code...
            $table->string('subtitle')->nullable(); // Add the 'subtitle' column
            // ...existing code...
        });
    }

    public function down(): void
    {
        Schema::table('rental_resale', function (Blueprint $table) {
            // ...existing code...
            $table->dropColumn('subtitle'); // Remove the 'subtitle' column
            // ...existing code...
        });
    }
};

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
        Schema::table('offplans', function (Blueprint $table) {
            $table->string('slug')->unique()->after('title'); // Add the slug column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offplans', function (Blueprint $table) {
            $table->dropColumn('slug'); // Remove the slug column
        });
    }
};

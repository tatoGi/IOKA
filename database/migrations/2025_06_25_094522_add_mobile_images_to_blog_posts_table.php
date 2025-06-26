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
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('mobile_image')->nullable();
            $table->string('mobile_image_alt')->nullable();
            $table->string('mobile_banner_image')->nullable();
            $table->string('mobile_banner_image_alt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn('mobile_image');
            $table->dropColumn('mobile_image_alt');
            $table->dropColumn('mobile_banner_image');
            $table->dropColumn('mobile_banner_image_alt');
        });
    }
};

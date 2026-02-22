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
    // Cek dulu apakah tabel products sudah ada
    if (Schema::hasTable('products')) {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('slug');
        });
    }
    
    // Cek dulu apakah tabel product_images sudah ada
    if (Schema::hasTable('product_images')) {
        Schema::table('product_images', function (Blueprint $table) {
            $table->boolean('is_primary')->default(false)->after('image_path');
        });
    }
}
};

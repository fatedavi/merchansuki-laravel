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
        Schema::table('product_variants', function (Blueprint $table) {
            // Drop unique constraint if exists on SKU
            $table->dropUnique(['sku']);
            
            $table->renameColumn('name', 'variant_name');
            $table->integer('price')->change();
            $table->string('image')->nullable()->after('stock');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('image');
            
            // Drop unused columns
            $table->dropColumn(['sku', 'color', 'size', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->renameColumn('variant_name', 'name');
            $table->decimal('price', 15, 2)->default(0)->change();
            $table->dropColumn(['image', 'status']);
            
            $table->string('sku')->unique()->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }
};

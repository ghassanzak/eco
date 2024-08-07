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
        Schema::create('products_tags', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->nullOnDelete();
            $table->foreignId('tag_id')->constrained()->nullOnDelete();
            $table->primary(['product_id', 'tag_id'], 'products_tags_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_tags');
    }
};

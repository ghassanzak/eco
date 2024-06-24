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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->bigInteger('category_id');
            $table->bigInteger('user_id');
            $table->string('code')->nullable();
            $table->string('brand')->nullable();
            $table->decimal('current_purchase_cost', 11, 3);
            $table->decimal('current_sale_price', 11, 3)->nullable();
            $table->decimal('available_quantity', 11, 3)->default(0);
            $table->text('description')->nullable();
            $table->tinyInteger('is_popular')->nullable()->comment('0=no,1=yes');
            $table->tinyInteger('is_trending')->nullable()->comment('0=no,1=yes');
            $table->tinyInteger('status')->default(1)->comment('0=inactive,1=active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

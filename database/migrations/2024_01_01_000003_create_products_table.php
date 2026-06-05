<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('category', ['makanan', 'minuman', 'fashion', 'jasa', 'lainnya'])->default('lainnya');
            $table->unsignedInteger('price');
            $table->unsignedInteger('stock')->default(0);
            $table->string('image_path')->nullable();   // path foto produk
            $table->string('emoji', 10)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_banned')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

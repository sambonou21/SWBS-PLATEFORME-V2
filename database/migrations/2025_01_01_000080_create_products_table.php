<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_description');
            $table->text('description');
            $table->decimal('price_fcfa', 12, 2);
            $table->boolean('is_active')->default(true);
            $table->integer('stock')->default(0);
            $table->string('type')->default('standard'); // standard, service, digital, affiliate, physical
            $table->string('download_url')->nullable(); // pour les produits digitaux (livres, templates, etc.)
            $table->string('external_url')->nullable(); // pour les produits d'affiliation
            $table->string('main_image_path')->nullable();
            $table->json('gallery')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
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
        Schema::create('url_rewrites', function (Blueprint $table) {
            $table->id();
            $table->ulid('tenant_id');
            $table->string('locale', 10);
            $table->string('slug');
            
            // Polymorphic relations (Category, Brand, Product)
            $table->ulid('target_id');
            $table->string('target_type');
            
            // SEO & Routing fields
            $table->boolean('is_active')->default(true);
            $table->string('redirect_type', 3)->nullable(); // e.g. 301, 302
            
            $table->timestamps();

            // Foreign key to tenants
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');

            // The Ultimate Defense: Same slug cannot exist twice in the same locale for the same tenant.
            $table->unique(['tenant_id', 'locale', 'slug']);
            
            // Fast lookup for routing
            $table->index(['tenant_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('url_rewrites');
    }
};

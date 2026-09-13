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
        Schema::create('brands', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('tenant_id');
            $table->json('name');
            $table->json('slug');
            $table->ulid('logo_id')->nullable();
            
            $table->timestamps();

            // Foreign keys
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            // Unique constraint using generated column or simply relying on application logic?
            // Since MySQL JSON extract unique indexes require generated columns in older versions, 
            // but Laravel handles it well or we can just enforce it on the primary locale or use a simple unique index on a virtual column.
            // Let's add a virtual column for the primary slug to enforce uniqueness per tenant.
            $table->string('primary_slug')->virtualAs("slug->>'$.tr'");
            $table->unique(['tenant_id', 'primary_slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};

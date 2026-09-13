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
        Schema::create('categories', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('tenant_id');
            $table->ulid('parent_id')->nullable();
            
            $table->json('name');
            $table->json('slug');
            
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(true);
            
            $table->timestamps();

            // Foreign keys
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('restrict');
            
            // To prevent cross-tenant parents, we can add a composite foreign key
            // First we need a unique constraint on (tenant_id, id) for categories to reference it
            $table->unique(['tenant_id', 'id']);
            
            // Add the composite foreign key for parent category to strictly isolate tenants
            $table->foreign(['tenant_id', 'parent_id'])
                  ->references(['tenant_id', 'id'])
                  ->on('categories')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

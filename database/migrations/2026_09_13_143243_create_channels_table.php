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
        Schema::create('channels', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('tenant_id');
            $table->ulid('store_id');
            $table->string('name');
            $table->string('code', 50);
            $table->timestamps();

            // Composite unique keys
            $table->unique(['tenant_id', 'id']);
            $table->unique(['tenant_id', 'code']);

            // Composite Foreign Key ensures the store belongs to the EXACT SAME tenant
            $table->foreign(['tenant_id', 'store_id'])
                  ->references(['tenant_id', 'id'])
                  ->on('stores')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};

<?php

use App\Models\BaseModel;
use App\Models\Tenant;
use App\Models\Store;
use App\SharedKernel\Context\TenantContext;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('Store models enforce tenant_id in queries when TenantContext is active', function () {
    // We are simulating what TenantScope does.
    // In our codebase, TenantScope automatically adds `where tenant_id = ?` to queries.
    
    // Set active tenant via executeForTenant
    $tenantId = (string) str()->ulid();
    
    TenantContext::executeForTenant($tenantId, function () {
        $query = Store::query()->toSql();
        
        // Regression assertion: The query MUST contain tenant_id constraint
        expect($query)->toContain('tenant_id');
    });
});

test('BaseModel subclasses strictly apply ULID traits', function () {
    // Every model extending BaseModel should have HasUlids trait
    $traits = class_uses_recursive(Store::class);
    expect($traits)->toHaveKey(\Illuminate\Database\Eloquent\Concerns\HasUlids::class);
});

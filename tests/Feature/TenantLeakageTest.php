<?php

use App\Models\Tenant;
use App\Models\Store;
use App\SharedKernel\Context\TenantContext;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Generate dummy tenants
    $this->tenantA = Tenant::create(['id' => (string) str()->ulid(), 'name' => 'Tenant A', 'slug' => 'tenant-a']);
    $this->tenantB = Tenant::create(['id' => (string) str()->ulid(), 'name' => 'Tenant B', 'slug' => 'tenant-b']);
    
    // Create a store for Tenant B
    $this->storeB = Store::create([
        'id' => (string) str()->ulid(),
        'tenant_id' => $this->tenantB->id,
        'name' => 'Store B'
    ]);
});

test('Tenant A cannot read Tenant B stores', function () {
    // Simulate Context switching to Tenant A
    TenantContext::setTenantId($this->tenantA->id);
    
    // Attempt to query Store B
    $foundStore = Store::find($this->storeB->id);
    
    // It should be completely invisible due to TenantScope
    expect($foundStore)->toBeNull();
});

test('Tenant A trying to fetch Store B throws 404', function () {
    TenantContext::setTenantId($this->tenantA->id);
    
    // Using fail-fast query
    expect(fn() => Store::findOrFail($this->storeB->id))
        ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
});

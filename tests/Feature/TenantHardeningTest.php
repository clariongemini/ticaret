<?php

use App\Models\Tenant;
use App\Models\Store;
use App\SharedKernel\Context\TenantContext;
use App\Exceptions\TenantIsolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->tenantA = Tenant::create(['id' => (string) str()->ulid(), 'name' => 'Tenant A', 'slug' => 'tenant-a']);
    $this->tenantB = Tenant::create(['id' => (string) str()->ulid(), 'name' => 'Tenant B', 'slug' => 'tenant-b']);
    
    // Create a store for Tenant A and Tenant B using System Context (to bypass our new protections for setup)
    TenantContext::executeAsSystem(function () {
        $this->storeA = Store::create([
            'id' => (string) str()->ulid(),
            'tenant_id' => $this->tenantA->id,
            'name' => 'Store A'
        ]);

        $this->storeB = Store::create([
            'id' => (string) str()->ulid(),
            'tenant_id' => $this->tenantB->id,
            'name' => 'Store B'
        ]);
    });
});

afterEach(function () {
    TenantContext::clear();
});

test('No-Context Denial: querying without context throws exception', function () {
    expect(fn() => Store::all())
        ->toThrow(TenantIsolationException::class, 'TenantScope is fail-closed');
});

test('Cross-Tenant Create: creating store for another tenant throws exception', function () {
    TenantContext::executeForTenant($this->tenantA->id, function () {
        expect(fn() => Store::create([
            'id' => (string) str()->ulid(),
            'tenant_id' => $this->tenantB->id,
            'name' => 'Malicious Store'
        ]))->toThrow(TenantIsolationException::class, 'Cross-tenant creation is blocked');
    });
});

test('Auto-Inject Tenant ID: creating store without tenant_id auto-injects it', function () {
    TenantContext::executeForTenant($this->tenantA->id, function () {
        $store = Store::create([
            'id' => (string) str()->ulid(),
            'name' => 'Valid Store'
        ]);

        expect($store->tenant_id)->toBe($this->tenantA->id);
    });
});

test('Cross-Tenant Update: modifying tenant_id throws exception', function () {
    TenantContext::executeForTenant($this->tenantA->id, function () {
        $store = Store::find($this->storeA->id);
        
        expect(fn() => $store->update(['tenant_id' => $this->tenantB->id]))
            ->toThrow(TenantIsolationException::class, 'Modifying tenant_id is blocked');
    });
});

test('Bulk Delete Isolation: delete without context is blocked', function () {
    expect(fn() => Store::where('name', 'Store A')->delete())
        ->toThrow(TenantIsolationException::class, 'TenantScope is fail-closed');
});

test('System Context Bypass: System can query all tenants', function () {
    TenantContext::executeAsSystem(function () {
        $stores = Store::all();
        expect($stores)->toHaveCount(2);
    });
});

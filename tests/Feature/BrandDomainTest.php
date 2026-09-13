<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Tenant;
use App\SharedKernel\Context\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class BrandDomainTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        TenantContext::executeAsSystem(function () {
            $this->tenant = Tenant::create([
                'name' => 'Test Tenant',
                'slug' => 'test-tenant-' . Str::random(6),
            ]);
        });
    }

    public function test_can_create_brand_with_translations()
    {
        TenantContext::executeForTenant($this->tenant->id, function () {
            $brand = Brand::create([
                'name' => [
                    'tr' => 'Apple TR',
                    'en' => 'Apple',
                ],
                'slug' => [
                    'tr' => 'apple-tr',
                    'en' => 'apple',
                ],
            ]);

            $this->assertDatabaseHas('brands', [
                'id' => $brand->id,
                'tenant_id' => $this->tenant->id,
            ]);

            $this->assertEquals('Apple TR', $brand->getTranslation('name', 'tr'));
            $this->assertEquals('Apple', $brand->getTranslation('name', 'en'));
            
            // Test fallback
            config(['app.fallback_locale' => 'en']);
            $this->assertEquals('Apple', $brand->getTranslation('name', 'de'));
            
            // Test setTranslation
            $brand->setTranslation('name', 'de', 'Apple DE');
            $brand->save();
            
            $brand->refresh();
            $this->assertEquals('Apple DE', $brand->getTranslation('name', 'de'));
        });
    }

    public function test_brands_are_isolated_by_tenant()
    {
        // System context creates a second tenant
        $tenant2 = TenantContext::executeAsSystem(function () {
            return Tenant::create([
                'name' => 'Other Tenant',
                'slug' => 'other-tenant-' . Str::random(6),
            ]);
        });

        // Tenant 1 creates a brand
        TenantContext::executeForTenant($this->tenant->id, function () {
            Brand::create([
                'name' => ['en' => 'Tenant 1 Brand'],
                'slug' => ['en' => 'tenant-1-brand'],
            ]);
        });

        // Tenant 2 shouldn't see Tenant 1's brand
        TenantContext::executeForTenant($tenant2->id, function () {
            $this->assertCount(0, Brand::all());
            
            Brand::create([
                'name' => ['en' => 'Tenant 2 Brand'],
                'slug' => ['en' => 'tenant-2-brand'],
            ]);
            
            $this->assertCount(1, Brand::all());
        });
    }
}

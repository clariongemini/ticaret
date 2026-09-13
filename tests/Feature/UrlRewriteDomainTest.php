<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Tenant;
use App\Models\UrlRewrite;
use App\SharedKernel\Context\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class UrlRewriteDomainTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant1;
    protected Tenant $tenant2;

    protected function setUp(): void
    {
        parent::setUp();

        TenantContext::executeAsSystem(function () {
            $this->tenant1 = Tenant::create([
                'name' => 'Test Tenant 1',
                'slug' => 'test-tenant-1-' . Str::random(6),
            ]);

            $this->tenant2 = Tenant::create([
                'name' => 'Test Tenant 2',
                'slug' => 'test-tenant-2-' . Str::random(6),
            ]);
        });
    }

    public function test_can_create_url_rewrite_for_category()
    {
        TenantContext::executeForTenant($this->tenant1->id, function () {
            $category = Category::create([
                'name' => ['en' => 'Electronics'],
                'slug' => ['en' => 'electronics'],
            ]);

            $rewrite = $category->urlRewrites()->create([
                'locale' => 'en',
                'slug' => 'electronics',
            ]);

            $this->assertDatabaseHas('url_rewrites', [
                'tenant_id' => $this->tenant1->id,
                'locale' => 'en',
                'slug' => 'electronics',
                'target_id' => $category->id,
                'target_type' => Category::class,
            ]);
        });
    }

    public function test_cannot_create_duplicate_slug_in_same_locale()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        $this->expectExceptionMessage('Duplicate entry'); // MySQL unique constraint

        TenantContext::executeForTenant($this->tenant1->id, function () {
            $cat1 = Category::create(['name' => ['en' => 'Cat1'], 'slug' => ['en' => 'cat1']]);
            $cat2 = Category::create(['name' => ['en' => 'Cat2'], 'slug' => ['en' => 'cat2']]);

            $cat1->urlRewrites()->create([
                'locale' => 'en',
                'slug' => 'common-slug',
            ]);

            $cat2->urlRewrites()->create([
                'locale' => 'en',
                'slug' => 'common-slug',
            ]);
        });
    }

    public function test_can_create_same_slug_in_different_locales()
    {
        TenantContext::executeForTenant($this->tenant1->id, function () {
            $cat1 = Category::create(['name' => ['en' => 'Cat1'], 'slug' => ['en' => 'cat1']]);

            $cat1->urlRewrites()->create([
                'locale' => 'en',
                'slug' => 'telefon',
            ]);

            $cat1->urlRewrites()->create([
                'locale' => 'tr',
                'slug' => 'telefon',
            ]);

            $this->assertDatabaseCount('url_rewrites', 2);
        });
    }

    public function test_can_create_same_slug_in_different_tenants()
    {
        $catId1 = null;
        TenantContext::executeForTenant($this->tenant1->id, function () use (&$catId1) {
            $cat1 = Category::create(['name' => ['en' => 'Cat1'], 'slug' => ['en' => 'cat1']]);
            $cat1->urlRewrites()->create([
                'locale' => 'en',
                'slug' => 'phones',
            ]);
            $catId1 = $cat1->id;
        });

        TenantContext::executeForTenant($this->tenant2->id, function () {
            $cat2 = Category::create(['name' => ['en' => 'Cat2'], 'slug' => ['en' => 'cat2']]);
            $cat2->urlRewrites()->create([
                'locale' => 'en',
                'slug' => 'phones', // Same slug, different tenant
            ]);
        });

        $this->assertDatabaseCount('url_rewrites', 2);
    }
}

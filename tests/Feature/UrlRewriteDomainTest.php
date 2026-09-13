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

    public function test_cannot_create_url_rewrite_for_cross_tenant_target()
    {
        $tenant2Cat = null;

        // Create a category under tenant2
        TenantContext::executeForTenant($this->tenant2->id, function () use (&$tenant2Cat) {
            $tenant2Cat = Category::create([
                'name' => ['en' => 'Tenant2 Category'],
                'slug' => ['en' => 'tenant2-cat'],
            ]);
        });

        // Now attempt to create a UrlRewrite under tenant1 pointing to tenant2's category
        $this->expectException(\App\Exceptions\TenantIsolationException::class);
        $this->expectExceptionMessage('Cross-tenant UrlRewrite is blocked');

        TenantContext::executeForTenant($this->tenant1->id, function () use ($tenant2Cat) {
            UrlRewrite::create([
                'locale' => 'en',
                'slug' => 'phones',
                'target_id' => $tenant2Cat->id,
                'target_type' => Category::class,
            ]);
        });
    }

    public function test_url_rewrite_id_is_ulid()
    {
        TenantContext::executeForTenant($this->tenant1->id, function () {
            $cat = Category::create(['name' => ['en' => 'Cat'], 'slug' => ['en' => 'cat']]);
            $rewrite = $cat->urlRewrites()->create([
                'locale' => 'en',
                'slug' => 'cat-slug',
            ]);

            // ULID is a 26-character alphanumeric string
            $this->assertMatchesRegularExpression('/^[0-9A-Z]{26}$/i', $rewrite->id);
        });
    }
}

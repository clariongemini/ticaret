<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Tenant;
use App\SharedKernel\Context\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CategoryDomainTest extends TestCase
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

    public function test_can_create_category_hierarchy()
    {
        TenantContext::executeForTenant($this->tenant->id, function () {
            $electronics = Category::create([
                'name' => ['en' => 'Electronics'],
                'slug' => ['en' => 'electronics'],
                'sort_order' => 1,
            ]);

            $phones = Category::create([
                'parent_id' => $electronics->id,
                'name' => ['en' => 'Phones'],
                'slug' => ['en' => 'phones'],
                'sort_order' => 1,
            ]);

            $this->assertDatabaseHas('categories', [
                'id' => $electronics->id,
                'parent_id' => null,
            ]);

            $this->assertDatabaseHas('categories', [
                'id' => $phones->id,
                'parent_id' => $electronics->id,
            ]);

            $this->assertEquals($electronics->id, $phones->parent->id);
            $this->assertCount(1, $electronics->children);
            $this->assertEquals($phones->id, $electronics->children->first()->id);
        });
    }

    public function test_categories_are_isolated_by_tenant()
    {
        // System context creates a second tenant
        $tenant2 = TenantContext::executeAsSystem(function () {
            return Tenant::create([
                'name' => 'Other Tenant',
                'slug' => 'other-tenant-' . Str::random(6),
            ]);
        });

        // Tenant 1 creates a category
        TenantContext::executeForTenant($this->tenant->id, function () {
            Category::create([
                'name' => ['en' => 'Tenant 1 Category'],
                'slug' => ['en' => 'tenant-1-category'],
            ]);
        });

        // Tenant 2 shouldn't see Tenant 1's category
        TenantContext::executeForTenant($tenant2->id, function () {
            $this->assertCount(0, Category::all());
            
            Category::create([
                'name' => ['en' => 'Tenant 2 Category'],
                'slug' => ['en' => 'tenant-2-category'],
            ]);
            
            $this->assertCount(1, Category::all());
        });
    }

    public function test_cannot_set_parent_category_from_another_tenant()
    {
        $tenant2 = TenantContext::executeAsSystem(function () {
            return Tenant::create([
                'name' => 'Other Tenant',
                'slug' => 'other-tenant-' . Str::random(6),
            ]);
        });

        $tenant1CategoryId = null;

        TenantContext::executeForTenant($this->tenant->id, function () use (&$tenant1CategoryId) {
            $cat = Category::create([
                'name' => ['en' => 'Tenant 1 Category'],
                'slug' => ['en' => 'tenant-1-category'],
            ]);
            $tenant1CategoryId = $cat->id;
        });

        $this->expectException(\Illuminate\Database\QueryException::class);
        $this->expectExceptionMessage('foreign key constraint fails'); // MySQL will throw composite FK error

        TenantContext::executeForTenant($tenant2->id, function () use ($tenant1CategoryId) {
            // Attempting to use a parent_id from another tenant
            // The DB constraint (tenant_id, parent_id) -> (tenant_id, id) should catch this.
            Category::create([
                'parent_id' => $tenant1CategoryId,
                'name' => ['en' => 'Tenant 2 Category'],
                'slug' => ['en' => 'tenant-2-category'],
            ]);
        });
    }
    public function test_cannot_delete_category_with_children()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        $this->expectExceptionMessage('foreign key constraint fails'); // MySQL restrict error

        TenantContext::executeForTenant($this->tenant->id, function () {
            $parent = Category::create([
                'name' => ['en' => 'Parent'],
                'slug' => ['en' => 'parent'],
            ]);

            Category::create([
                'parent_id' => $parent->id,
                'name' => ['en' => 'Child'],
                'slug' => ['en' => 'child'],
            ]);

            // Attempt to delete parent should fail due to RESTRICT policy
            $parent->delete();
        });
    }

    public function test_cannot_create_category_cycle()
    {
        TenantContext::executeForTenant($this->tenant->id, function () {
            $a = Category::create(['name' => ['en' => 'A'], 'slug' => ['en' => 'a']]);
            $b = Category::create(['parent_id' => $a->id, 'name' => ['en' => 'B'], 'slug' => ['en' => 'b']]);
            
            // B -> A is valid so far

            // Prevent self assignment
            $this->expectException(\DomainException::class);
            $this->expectExceptionMessage("A category cannot be its own parent.");
            $a->update(['parent_id' => $a->id]);
        });
    }

    public function test_cannot_create_category_cycle_deep()
    {
        TenantContext::executeForTenant($this->tenant->id, function () {
            $a = Category::create(['name' => ['en' => 'A'], 'slug' => ['en' => 'a']]);
            $b = Category::create(['parent_id' => $a->id, 'name' => ['en' => 'B'], 'slug' => ['en' => 'b']]);
            $c = Category::create(['parent_id' => $b->id, 'name' => ['en' => 'C'], 'slug' => ['en' => 'c']]);
            
            // Prevent C being parent of A (A -> B -> C -> A)
            $this->expectException(\DomainException::class);
            $this->expectExceptionMessage("Category hierarchy cycle detected.");
            $a->update(['parent_id' => $c->id]);
        });
    }
}

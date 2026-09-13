# ADR-013: URL Routing & Category Hierarchy Policies

## Status
Accepted

## Context
In an i18n-first e-commerce architecture, URL slugs (`telefonlar`, `phones`) are critical for SEO and localized routing. Previously, slugs were stored purely in a JSON column within models (`Brand`, `Category`) and uniqueness was enforced on a hardcoded primary locale (`$.tr`). This broke i18n rules, meaning English slugs had no uniqueness guarantees and could clash.

Furthermore, the Adjacency List pattern used for the `categories` hierarchy needs strict deletion (`RESTRICT`) and cycle-prevention policies to avoid corrupting the taxonomy.

## Decision

1. **Polymorphic `url_rewrites` Table:**
   We will decouple SEO/URL routing from the domain entities. A dedicated `url_rewrites` table will be introduced to handle all routing and enforce strict uniqueness per locale.
   - Schema will include: `tenant_id`, `locale`, `slug`, `target_type`, `target_id`.
   - A strict DB composite unique index will be applied on `(tenant_id, locale, slug)`.
   - The JSON `slug` column in domain entities (Brand, Category) will remain for Data Transfer / API payloads, but `url_rewrites` will serve as the projection and authoritative source for routing.

2. **Category Hierarchy Deletion Policy (`RESTRICT`):**
   - We will NOT use `onDelete('cascade')` for the category `parent_id` foreign key.
   - Instead, we will use `onDelete('restrict')`. If a parent category is to be deleted, the admin MUST explicitly re-parent or delete its children first. This prevents catastrophic accidental deletion of large catalog sub-trees.

3. **Category Cycle Prevention:**
   - MySQL cannot natively prevent cycles in adjacency lists without triggers.
   - Cycle prevention (e.g. A -> B -> A) will be enforced strictly in the Application Layer (Domain Service). Any assignment of a `parent_id` will verify that the new parent is not a descendant of the target category.

## Consequences
- **Positive:** True multilingual routing. Unique URLs are guaranteed across all supported locales at the database level.
- **Positive:** Protection against catastrophic data loss in catalog taxonomy.
- **Negative:** Extra query overhead for routing, and synchronization logic required between the Model's JSON slug and the `url_rewrites` projection.
- **Negative:** Cycle prevention relies on application logic, which means bulk DB imports must be handled via domain services or carefully orchestrated.

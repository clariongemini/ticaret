# ADR-009: Product and Variant Architecture

## Status
Accepted

## Context
In a multi-tenant e-commerce platform, products can be either simple standalone items or have multiple variations (e.g., sizes, colors). The traditional Magento approach is to have "Simple" and "Configurable" products sharing the same table (`type_id`). However, this leads to complex domain logic where a single table holds both abstract definitions and physical purchasable items.

## Decision
We will adopt a **Strict Parent-Variant architecture**:
- `products`: Represents the abstract base product (e.g., "iPhone 15"). It does NOT have a SKU.
- `product_variants`: Represents the physical, purchasable item (e.g., "iPhone 15 - Black - 256GB"). It MUST have a SKU.

Simple products are strictly modeled as a `product` having exactly one `product_variant`.

## Consequences
- **Positive:** Clear separation of concerns. Pricing, inventory, and cart logic ALWAYS target `product_variants`, simplifying the downstream domains.
- **Positive:** Cleaner database model and foreign keys.
- **Negative:** Creating a "simple" product now requires inserting two records (one product, one variant). This will be abstracted away by Repositories/Services.

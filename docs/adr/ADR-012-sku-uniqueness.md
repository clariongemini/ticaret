# ADR-012: Tenant-Isolated SKU Uniqueness

## Status
Accepted

## Context
SKU (Stock Keeping Unit) uniqueness defines whether an item is globally unique across the platform (like an ISBN or GTIN) or unique per merchant (tenant).

## Decision
SKUs will be **Tenant-Isolated**. 
The database will enforce `UNIQUE(tenant_id, sku)` on the `product_variants` table.

## Consequences
- **Positive:** Merchants can use their own internal SKU systems without colliding with other merchants.
- **Positive:** Follows strict bounded context isolation.
- **Negative:** If the platform ever evolves into a single unified marketplace catalog, matching products will have to be done via a separate global identifier (e.g., `gtin` or `barcode`), not SKU.

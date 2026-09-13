# ADR-010: Hybrid Typed EAV Data Model

## Status
Accepted

## Context
E-commerce products have highly dynamic attributes (e.g., size, color, material). Storing all of them as standard columns is impossible. Classic Entity-Attribute-Value (EAV) is flexible but heavily degrades performance at scale, especially for filtering and sorting. Pure JSON columns are opaque, hard to index properly for range queries in standard MySQL (without virtual columns), and lack strict type constraints.

## Decision
We will implement a **Hybrid Typed EAV model**:
- Core searchable/filterable attributes will use `product_attribute_values` with typed columns (`value_string`, `value_integer`, `value_decimal`, `value_boolean`, `value_date`).
- Unstructured or complex data will use `value_json`.

## Consequences
- **Positive:** MySQL B-Tree indexes can be efficiently utilized on `(tenant_id, attribute_id, value_string)` or `value_decimal`.
- **Positive:** Strict typing at the database level for attribute values.
- **Negative:** Table width increases. Queries to load a full product require multiple joins or separate SELECTs. We will rely on Repositories to hydrate entities efficiently.

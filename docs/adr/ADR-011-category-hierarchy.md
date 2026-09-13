# ADR-011: Adjacency List with CTE for Category Hierarchy

## Status
Accepted

## Context
Categories form a hierarchical tree. Options include Adjacency List (`parent_id`), Nested Sets (left/right values), Materialized Path, or Closure Table. Nested Sets provide very fast reads but writes (tree moves, inserts) cause massive cascading updates, blocking the table and generating huge outbox events.

## Decision
Since we use MySQL 8.0+, we will use **Adjacency List (`parent_id`) combined with Recursive CTEs** for tree reads.

## Consequences
- **Positive:** Very simple schema (just `parent_id`).
- **Positive:** Writes are instant. Moving a sub-tree is a single O(1) update.
- **Positive:** Recursive CTEs in MySQL 8.0 perform excellently for typical category tree depths (usually < 10 levels).
- **Negative:** Requires slightly more complex SQL queries for fetching descendants/ancestors compared to Nested Sets.

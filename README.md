# Antigravity E-Commerce Core Platform

**System Architect:** Ulaş Kaşıkcı  
**Version:** 1.2.0 (Phase 0 - Architectural Foundation)  
**Type:** Enterprise Commerce Core / Backend Infrastructure  

![Architecture](https://img.shields.io/badge/Architecture-Modular_Monolith-blue)
![API](https://img.shields.io/badge/API-Headless_|_REST-green)
![DB](https://img.shields.io/badge/Database-MySQL_8%2B-orange)
![Status](https://img.shields.io/badge/Status-Hardening_Phase-yellow)

---

## Abstract

The **Antigravity E-Commerce Core Platform** is an enterprise-grade, highly scalable backend infrastructure designed to support complex B2C, B2B, and SaaS marketplace models. Abandoning traditional monolithic spaghetti architectures, Antigravity introduces a **Strict Modular Monolith** approach coupled with **Event-Driven** communication and an **API-First, Headless** paradigm. 

The system acts as a centralized, highly concurrent commerce engine capable of serving omni-channel frontends (Web, Mobile, B2B Portals) and AI Agents with zero business logic leakage to the presentation layers.

## Core Architectural Principles

The platform's engineering foundation is strictly governed by the [Architecture Master Specification](ARCHITECTURE_MASTER_SPEC.md). 

1. **Strict Modular Monolith:** Logical boundaries separate business domains (Catalog, Order, Payment). Cross-domain synchronous SQL joins are strictly prohibited. Inter-domain communication relies on domain events and read-only API contracts.
2. **API-First & Headless Agnosticism:** The backend remains completely agnostic to the frontend implementation. Server-Side Rendering (SSR) is decoupled as a storefront requirement.
3. **Pragmatic Event-Driven Design:** Only domain-significant state transitions (e.g., `OrderPlaced`, `InventoryReserved`) generate events, preventing system noise from standard CRUD operations.
4. **Multi-Tenant Isolation:** Supports infinite tenants, stores, and channels isolated via global scope indexing and strictly enforced `tenant_id` constraints on a single, highly optimized MySQL 8+ database.

## Key Subsystems & Invariants

* **Canonical Inventory & Reservation State Machine:** Stock is calculated dynamically via an immutable ledger (`available = on_hand - reserved - committed`). Soft allocations are managed through strict `PENDING → RESERVED → COMMITTED` state machines to prevent race conditions during high-traffic checkouts.
* **Hybrid Attribute Engine:** Moves beyond simple EAV or pure JSON limits. Utilizes a strongly typed relational metadata model (`urun_tipleri`, `ozellik_tanimlari`) with JSON projections for high-performance filtering and variant generation.
* **Idempotent Payment & Webhook Deduplication:** Financial consistency is guaranteed via `Idempotency-Key` constraints for side-effect-sensitive mutations. Webhooks are secured via `INSERT-first` deduplication (`UNIQUE` provider event IDs) and HMAC SHA256 payload hashing.
* **Advanced Rule & Pricing Engine:** Context-aware price resolution based on priority chains (Base → B2B → Tier → Promo). The rule engine utilizes Abstract Syntax Trees (AST) for secure, predictable promotion evaluations.
* **AI Commerce API with Risk-Level Authorization:** Exposes a function-calling API specifically designed for AI/LLM integration (RAG), guarded by a risk-level authorization matrix (e.g., Read operations are Low Risk; Checkout mutations require Explicit User Confirmation).

## System Governance & Documentation

Development within the Antigravity Core is highly regulated. Developers must adhere to the following governance documents:

* 🏛️ **[Architecture Master Spec (v1.2)](ARCHITECTURE_MASTER_SPEC.md)** - The definitive engineering constitution and system invariants.
* 📋 **[Master Checklist (YAPILACAKLAR.md)](yapilacaklar.md)** - The 10-tier, 36-step execution plan and Quality Gates.
* 🎯 **[Project Vision (PROJE_DETAY.md)](PROJE_DETAY.md)** - Executive summary and enterprise requirements.
* 📂 **[/docs/planlama](docs/planlama/)** - Phase-by-phase planning, architectural analysis, and testing requirements.
* 📂 **[/docs/adr](docs/adr/)** - Architecture Decision Records tracking critical technology choices.

---

*Designed and architected by Ulaş Kaşıkcı. All engineering commits must pass the F0-F34 Quality Gates before merging.*

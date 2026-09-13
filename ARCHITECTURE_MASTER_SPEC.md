# 🏛️ ANTIGRAVITY E-COMMERCE: ARCHITECTURE MASTER SPECIFICATION

**Versiyon:** 1.1.0  
**Durum:** TASLAK (Hardening Phase - F0)  
**Kapsam:** Kesin Mimari Kontratlar, Domain Sınırları ve Sistem İnvaryantları (Invariants)  

Bu belge, Antigravity E-Commerce platformunun inşasında yapay zeka ve mühendislik ekipleri tarafından uyulması **ZORUNLU** olan anayasal kuralları tanımlar. `PROJE_DETAY.md` dosyasındaki vizyonun uygulanabilir, test edilebilir ve ölçülebilir teknik spesifikasyonudur.

> [!CAUTION]
> Bu belgedeki kurallar esnetilemez. Mimari sınırları (Bounded Contexts) ihlal eden, doğrudan veritabanı bağlantısı kuran veya event-driven yapıyı bozan hiçbir kod (Pull Request) kabul edilmeyecektir.

---

## 01. Architecture Principles
*   **Strict Modular Monolith:** Modüller arası iletişim sadece Domain Event'ler veya salt-okunur (read-only) API kontratları üzerinden yapılır.
*   **Pragmatic Event-Driven:** Her teknik CRUD (mutation) değil, **sadece Domain-significant state transition'lar** event üretir (Örn: `OrderPlaced`, `InventoryReserved`). Event sistemi gürültüden arındırılmalıdır.
*   **Headless & API-First:** Platform agnostiktir. Frontend (Storefront) için özel endpoint veya business logic içeren BFF (Backend for Frontend) yazılamaz.
*   **Idempotency:** Side-effect üreten tüm kritik mutasyonlar (Örn: `CreateOrder`, `CapturePayment`) için `Idempotency-Key` zorunludur. Doğal idempotent işlemler (Örn: `PUT /profile`) için zorunlu değildir.

## 02. System Context
Sistem; Core Commerce API, Merchant Admin API, B2B Portal API, AI Commerce API ve Webhook Gateway giriş noktalarına sahiptir. Dış sistemlerle entegrasyon asenkron queue worker'lar üzerinden yapılır.

## 03. Domain Map
Sistem 4 ana domain grubundan oluşur:
1.  **Core Commerce:** Catalog, Inventory, Pricing.
2.  **Transaction:** Cart, Checkout, Order, Payment.
3.  **Customer & B2B:** Identity, B2B Ledger, CRM.
4.  **Operations:** Fulfillment, Shipping, Notification.

## 04. Bounded Context & Database Ownership Modeli
*   **Tek MySQL Veritabanı:** Altyapı olarak MySQL 8+ kullanılır.
*   **Logical Ownership:** Veritabanı tektir, ancak tablolar mantıksal olarak (prefix veya namespace ile) BC'lere aittir (Örn: `catalog_products`, `order_orders`).
*   **Ownership Boundary:** BC A, BC B'nin tablolarına doğrudan SQL YAZAMAZ ve BC B'nin tablolarını kendi repository'siyle OKUYAMAZ.

## 05. Module Dependency Rules
*   Bağımlılık yönü her zaman dıştan içe doğrudur (Onion Architecture).
*   Hiçbir modül `Order` modülüne doğrudan bağımlı olamaz.

## 06. Tenant / Store / Channel Model
*   **İzolasyon Modeli:** Logical Separation (Shared Database, Separate Tenant_ID).
*   Veritabanındaki her tabloda zorunlu `tenant_id` kolonu bulunur. Global tablolar hariçtir.
*   Sorgularda Global Scope ile `tenant_id` izolasyonu ORM/Query Builder seviyesinde zorunlu kılınmıştır.
*   Hiyerarşi: `Tenant -> Store -> Channel -> Locale & Currency`

## 07. Catalog Domain
Sistemin okuma yükü en yüksek domainidir. Okuma işlemleri CQRS prensibiyle ayrıştırılır.

## 08. Product Type & 09. Attribute Engine
*   **Hibrit Relational + JSON Modeli:** Salt JSONB veya EAV yerine, metadata relational tutulur.
*   Gerekli tablolar: `ozellik_tanimlari`, `ozellik_gruplari`, `urun_tipleri`, `urun_tip_ozellikleri`, `urun_ozellik_degerleri`, `varyant_ozellik_degerleri`.
*   Arama ve listeleme performansı için optimize edilmiş JSON projection (MySQL JSON tipinde) ve generated/functional indexler kullanılır.
*   Özellikler SEO, filtreleme, varyant oluşturma gibi davranışlara göre sınıflandırılır.

## 10. Variant Engine
Varyantlar (SKU), Product'ın fiziksel olarak satılabilir alt birimleridir. Ana ürün satılamaz, varyant satılır.

## 11. Pricing Engine
*   **Fiyat Çözümleme Zinciri (Resolution Priority):** `f(Tenant, Store, Channel, CustomerGroup, Customer, Product, Variant, Currency, Quantity, Promotion, Date) = Price`
*   B2B için Tier Pricing (Örn: 100+ adet) ve Müşteriye Özel Fiyatlar (VIP Müşteri) net bir hiyerarşiyle (priority) çözümlenir.

## 12. Category / Brand
Nested Set veya Materialized Path modeli.

## 13. Inventory
*   **Durum Modeli (State):** `on_hand`, `reserved`, `available`, `incoming`, `committed`, `damaged`, `returned` ayrımı zorunludur.
*   **Canonical Formula:** `available = on_hand - reserved - committed`
*   Mevcut durum materialize olarak tutulur. Her sorguda ledger history (event replay) çalıştırılmaz.

## 14. Reservation
*   **State Machine:** `PENDING -> RESERVED -> COMMITTED | RELEASED | EXPIRED | CANCELLED`
*   Payment success -> Commit reservation. Payment failure -> Release reservation. Payment timeout -> Wait. Webhook later arrives SUCCESS -> Commit if still valid.

## 15. Warehouse & 16. Stock Ledger
*   Stok hareketleri (IN/OUT) Ledger'da tutulur (Immutable history). Current inventory state ise güncellenir (Materialized).

## 17. Customer
Müşteri verileri KVKK/GDPR requirements-aware bir mimariyle saklanır (PII data encryption/masking, Right to be forgotten API'leri).

## 18. B2B & 19. Credit Ledger
*   Açık hesap limitleri finansal bir ledger olarak tutulur.
*   Kredi limiti aşımı (Overdraft) sıkı transactional kilitlerle (Race Condition korumasıyla) korunur.

## 20. Cart & 21. Checkout
Sepet fiyat ve stok doğrulamalarını canlı yapar. Checkout katı bir State Machine ile yönetilir.

## 22. Order
Sipariş durumu katı bir State Machine ile yönetilir (Örn: `Pending -> Authorized -> Processing -> Shipped -> Delivered`).

## 23. Payment & 24. Payment Gateway SDK Contract
*   **Capability Matrix:** Sağlayıcılar `supports3DS`, `supportsRecurring`, `supportsPartialRefund`, `supportsCapture`, `supportsVoid` gibi yeteneklerini deklare eder.
*   **Canonical Status:** Provider'a özel durumlar (Örn: `iyzico_status = SUCCESS`) core domain'e sızdırılamaz. Sadece Canonical durumlar (`AUTHORIZED`, `CAPTURED`, `FAILED`, `CANCELLED`, `REFUNDED`) kullanılır.

## 25. Refund & 26. Reconciliation
Kısmi iade desteği ve zorunlu External Provider Reference ID mutabakatı.

## 27. Shipping & 28. Tax & 29. Invoice
Dinamik kargo/vergi kuralları. Fatura immutable bir dökümandır.

## 30. RMA / Returns & 31. Promotion & 32. Rule Engine
İade State Machine'i ve AST (Abstract Syntax Tree) ile modellenen kural motoru.

## 33. Coupon & 34. Subscription & 35. Recurring Payment
Kuponlarda concurrency koruması, aboneliklerde Dunning (yeniden deneme) mekanizmaları.

## 36. CMS & 37. Media
Headless sayfa blokları ve optimize medya yönetimi.

## 38. Localization & 39. Translation
Sistemin string ve data değerleri i18n uyumludur.

## 40. SEO, 41. Schema.org, 42. Sitemap
*   SEO Entity Modeli: Meta Title, Description, Canonical/Hreflang.
*   SSR (Server-Side Rendering), backend invariant'ı değil, frontend/storefront uygulama zorunluluğudur (Implementation Requirement). Backend tamamen frontend-agnostic çalışır.

## 43. Merchant Center & 44. Search
Feed streaming yeteneği ve Typo-tolerant arama altyapısı.

## 45. AI Commerce API
*   Güvenli "Function Calling" API'si.
*   **Tool-Level Authorization:** AI ajanı, yetkisiz ödeme (Checkout) veya kritik state değişimi YAPAMAZ.
*   `create_checkout`, `create_payment_intent` gibi kritik işlemlerde AI ajanı "explicit user confirmation required" kuralına tabidir.

## 46. Authentication & 47. RBAC & 48. API Security
Oauth2/JWT, ABAC/RBAC kontrolleri.

## 49. Webhooks & 50. Events
*   **Idempotency & Replay:** Webhook teslimatları için katı tablo şeması (`event_id`, `provider_event_id`, `signature`, `received_at`, vb.).
*   **Deduplication:** `INSERT webhook_event WHERE provider_event_id UNIQUE` şeklinde Insert-first deduplication kuralı zorunludur. Aynı webhook event'inin birden fazla işlenmesi önlenir.

## 51. Outbox & 52. Queues
Transactional Outbox Pattern zorunluluğu.

## 53. Plugin Architecture & 54. Integration SDK
Core değiştirilmeden Hook/Service Container üzerinden eklenti geliştirme.

## 55. Admin Architecture & 56. Frontend Contract & 57. Mobile Contract
Tek merkezi API-first Commerce Core. BFF'nin Core'a (Business logic taşıyan katmana) dönüşmesi yasaktır.

## 58. Database Architecture & 59. Complete ERD & 60. Index Strategy & 61. Cache
MySQL 8+ altyapısı, B-Tree indeksler ve Tag-based cache.

## 62. Performance & 63. Observability & 64. Audit & 65. Security Threat Model
Eager Loading zorunluluğu, Tracing ve merkezi loglama.

## 66. KVKK / Data Governance
KVKK/GDPR requirements-aware architecture uygulanır. (Madde 17 ile bağlantılı).

## 67. Testing Architecture (68-70)
*   Sadece `%80 coverage` değil, sistem invariant'larını test eden (Inventory concurrency, payment idempotency, webhook replay, tenant isolation, B2B credit race vb.) Invariant/Concurrency testleri birincil kalite ölçütüdür.

## 71-83. CI/CD, Deployment, Definitions of Done & Architecture Gates
Sıfır kesinti, strict code review ve statik analiz süreçleri.

---
**KARAR:** Bu belge (v1.1), sistemin Hardening aşamasından geçmiş teknik anayasasıdır. Kodlama aşamasında F0 - F34 arasındaki modüller bu kurallara harfiyen uyarak inşa edilecektir.

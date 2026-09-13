# 🏛️ ANTIGRAVITY E-COMMERCE: ARCHITECTURE MASTER SPECIFICATION

**Versiyon:** 1.2.0  
**Durum:** TASLAK (Hardening Phase - F0)  
**Kapsam:** Kesin Mimari Kontratlar, Domain Sınırları ve Sistem İnvaryantları (Invariants)  

Bu belge, Antigravity E-Commerce platformunun inşasında yapay zeka ve mühendislik ekipleri tarafından uyulması **ZORUNLU** olan anayasal kuralları tanımlar. `PROJE_DETAY.md` dosyasındaki vizyonun uygulanabilir, test edilebilir ve ölçülebilir teknik spesifikasyonudur.

> [!CAUTION]
> Bu belgedeki kurallar esnetilemez. Mimari sınırları (Bounded Contexts) ihlal eden, doğrudan veritabanı bağlantısı kuran veya event-driven yapıyı bozan hiçbir kod (Pull Request) kabul edilmeyecektir.

---

## 01. Architecture Principles
*   **Strict Modular Monolith:** Modüller arası iletişim sadece Domain Event'ler veya salt-okunur (read-only) API kontratları üzerinden yapılır.
*   **Pragmatic Event-Driven:** Her teknik CRUD (mutation) değil, **sadece Domain-significant state transition'lar** ve dış dünyayı ilgilendiren business event'ler (Örn: `OrderPlaced`, `PaymentAuthorized`, `InventoryReserved`) event üretir. Event sistemi gürültüden arındırılmalıdır.
*   **Headless & API-First:** Platform agnostiktir. Frontend (Storefront) için özel endpoint veya business logic içeren BFF (Backend for Frontend) yazılamaz. Frontend-specific read composition olabilir ama business logic BFF'ye taşınamaz.
*   **Idempotency:** Doğal olarak idempotent HTTP operasyonları (Örn: `PUT /profile`) ile finansal/side-effect-sensitive mutation operasyonları ayrılır. Sadece kritik mutasyonlar (Örn: `POST /orders`, `POST /payments`, `POST /refunds`) için `Idempotency-Key` zorunludur.

## 02. System Context
Sistem; Core Commerce API, Merchant Admin API, B2B Portal API, AI Commerce API ve Webhook Gateway giriş noktalarına sahiptir. Dış sistemlerle entegrasyon asenkron queue worker'lar üzerinden yapılır.

## 03. Domain Map
Sistem 4 ana domain grubundan oluşur:
1.  **Core Commerce:** Catalog, Inventory, Pricing.
2.  **Transaction:** Cart, Checkout, Order, Payment.
3.  **Customer & B2B:** Identity, B2B Ledger, CRM.
4.  **Operations:** Fulfillment, Shipping, Notification.

## 04. Bounded Context & Database Ownership Modeli
*   **ONE MySQL DATABASE:** Altyapı olarak kesin teknoloji standardı MySQL 8+ tabanlı merkezi commerce core kurgusudur.
*   **Logical Ownership:** Veritabanı tektir, ancak tablolar mantıksal olarak BC'lere aittir (Örn: `catalog-owned tables`, `order-owned tables`).
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
*   **Hibrit Relational + JSON Modeli:** Salt JSONB yaklaşımı yeterli değildir. Özelliklerin tipleri, sırası, zorunluluğu, filtrelenebilirliği, çevirisi ve varyant oluşturup oluşturmadığı ayrıntılı modellenmelidir.
*   **Zorunlu Tablo Modeli:**
    *   `urun_tipleri`
    *   `ozellik_tanimlari`
    *   `ozellik_gruplari`
    *   `urun_tip_ozellikleri`
    *   `urun_ozellik_degerleri`
    *   `varyant_ozellik_degerleri`
*   Değer okuma hızlandırması ve filtreleme için projection katmanında MySQL JSON ve functional/generated indeksler kullanılabilir.

## 10. Variant Engine
Varyantlar (SKU), Product'ın fiziksel olarak satılabilir alt birimleridir. Ana ürün satılamaz, varyant satılır.

## 11. Pricing Engine
*   **Fiyat Çözümleme Zinciri (Resolution Priority):** `f(Tenant, Store, Channel, Product, Variant, Customer, Customer Group, B2B Company, Price List, Currency, Country, Quantity, Date/Time, Promotion, Tax Context) = Price`
*   **Resolution Contract Hiyerarşisi:** Base Price -> Customer Group Price -> B2B Contract Price -> Quantity Tier Price -> Channel Price -> Promotion.

## 12. Category / Brand
Nested Set veya Materialized Path modeli.

## 13. Inventory
*   **Durum Modeli (State):** En azından kavramsal olarak `on_hand`, `available`, `reserved`, `committed`, `incoming`, `damaged`, `returned` ayrımları ve invariant'ları tanımlanmalıdır.
*   **Canonical Formula:** `available = on_hand - reserved - committed`
*   **Stock Ledger:** Ledger immutable history'dir. Mevcut durum (current inventory state) materialize bir state'tir. Her sorguda ledger history (event replay) çalıştırılmaz.

## 14. Reservation
*   **State Machine:** `PENDING -> RESERVED -> COMMITTED` ve alternatif yollar (RELEASED, EXPIRED, CANCELLED).
*   Transaction invariant'ları nettir: Ödeme başarılıysa Commit reservation; Ödeme başarısızsa Release reservation; Timeout durumunda Wait; Webhook sonradan gelirse (SUCCESS) hala geçerliyse Commit edilir. Yarış durumları (Örn: Payment succeeded ama reservation expired) sıkı şekilde yönetilir.

## 15. Warehouse & 16. Stock Ledger
*   Stok hareketleri (IN/OUT) Ledger'da tutulur (Immutable history). Current inventory state ise güncellenir (Materialized).

## 17. Customer & 66. Data Governance
*   Müşteri verileri şifrelenmiş saklanır. Sistem **"KVKK/GDPR gereksinimlerini destekleyecek data-governance architecture"** ile inşa edilir. (Tek başına mimari hukuki uyumluluk vermez).

## 18. B2B & 19. Credit Ledger
*   Açık hesap limitleri finansal bir ledger olarak tutulur.
*   Kredi limiti aşımı (Overdraft) sıkı transactional kilitlerle (Race Condition korumasıyla) korunur.

## 20. Cart & 21. Checkout
Sepet fiyat ve stok doğrulamalarını canlı yapar. Checkout katı bir State Machine ile yönetilir.

## 22. Order
Sipariş durumu katı bir State Machine ile yönetilir (Örn: `Pending -> Authorized -> Processing -> Shipped -> Delivered`).

## 23. Payment & 24. Payment Gateway SDK Contract
*   **Capability Contract:** Interface içinde `authorize()`, `capture()`, `sale()`, `void()`, `refund()`, `partialRefund()`, `createPaymentIntent()`, `verifyWebhook()`, `parseWebhook()`, `getTransaction()` metodları bulunmalıdır.
*   **Capability Flags:** Sağlayıcılar `supports_3ds`, `supports_installments`, `supports_recurring`, `supports_partial_refund`, `supports_capture`, `supports_void`, `supports_tokenization`, `supports_hosted_checkout` yeteneklerini deklare eder.
*   **Canonical Status:** Provider'a özel durumlar (Örn: `iyzico_status = SUCCESS`) core domain'e sızdırılamaz. Core her zaman canonical statüler kullanır: `AUTHORIZED`, `CAPTURED`, `FAILED`, `CANCELLED`, `REFUNDED`.

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
*   **SSR Bağımsızlığı:** Commerce Core frontend-agnostic'tir. SSR (Server-Side Rendering), backend invariant'ı değil, Storefront SEO-critical sayfaları için uygulama zorunluluğudur (Implementation Requirement).

## 43. Merchant Center & 44. Search
Feed streaming yeteneği ve Typo-tolerant arama altyapısı.

## 45. AI Commerce API
*   Güvenli "Function Calling" API'si ve katı IAM/RBAC denetimi.
*   **Risk-Level Tool Authorization:** AI'nin araç erişimi risk seviyelerine göre ayrılır.
    *   Read (ürün oku, stok oku) -> **Low Risk**
    *   Cart Mutation (sepet oluştur, ekle/çıkar) -> **Medium Risk**
    *   Order/Payment (ödeme başlat) -> **High Risk** (Kesinlikle explicit user confirmation required kuralına tabidir).

## 46. Authentication & 47. RBAC & 48. API Security
Oauth2/JWT, ABAC/RBAC kontrolleri.

## 49. Webhooks & 50. Events
*   **Idempotency & Replay:** Webhook teslimatları için katı tablo şeması. `event_id`, `provider_event_id`, `provider_name`, `event_type`, `signature`, `received_at`, `processed_at`, `attempt_count`, `next_retry_at`, `status`, `failure_reason`, `idempotency_key`, `payload_hash` zorunlu kolonlardır.
*   **Deduplication:** `INSERT webhook_event WHERE provider_event_id UNIQUE` şeklinde Insert-first deduplication kuralı zorunludur. Aynı webhook event'inin birden fazla işlenmesi önlenir (duplicate ise ignore/replay-safe, değilse process mantığı).

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

## 67. Testing Architecture (68-70)
*   Sadece `%80 coverage` faydalıdır ama kalite ölçütü olarak tek başına kullanılamaz.
*   Sistem invariant'larını test eden testler (Inventory concurrency, payment idempotency, webhook replay, tenant isolation, B2B credit race, coupon race, authorization vb.) birincil kalite ölçütüdür.

## 71-83. CI/CD, Deployment, Definitions of Done & Architecture Gates
Sıfır kesinti, strict code review ve statik analiz süreçleri.

---
**KARAR:** Bu belge (v1.2.0), sistemin Hardening aşamasından geçmiş teknik anayasasıdır. Kodlama aşamasında F0 - F34 arasındaki modüller bu kurallara harfiyen uyarak inşa edilecektir.

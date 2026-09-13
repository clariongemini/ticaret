# 🏛️ ANTIGRAVITY E-COMMERCE: ARCHITECTURE MASTER SPECIFICATION

**Versiyon:** 1.0.0  
**Durum:** TASLAK (Mühendislik Onayı Bekleniyor)  
**Kapsam:** Kesin Mimari Kontratlar, Domain Sınırları ve Sistem İnvaryantları (Invariants)  

Bu belge, Antigravity E-Commerce platformunun inşasında yapay zeka ve mühendislik ekipleri tarafından uyulması **ZORUNLU** olan anayasal kuralları tanımlar. `PROJE_DETAY.md` dosyasındaki vizyonun uygulanabilir, test edilebilir ve ölçülebilir teknik spesifikasyonudur.

> [!CAUTION]
> Bu belgedeki kurallar esnetilemez. Mimari sınırları (Bounded Contexts) ihlal eden, doğrudan veritabanı bağlantısı kuran veya event-driven yapıyı bozan hiçbir kod (Pull Request) kabul edilmeyecektir.

---

## 01. Architecture Principles
*   **Strict Modular Monolith:** Modüller arası doğrudan veritabanı sorgusu veya senkron yazma (write) işlemi YASAKTIR. İletişim sadece Domain Event'ler veya salt-okunur (read-only) API kontratları üzerinden yapılır.
*   **Event-Driven by Default:** Her state (durum) değişikliği zorunlu olarak bir Domain Event fırlatır.
*   **Headless & API-First:** Frontend için özel endpoint yazılamaz. Tüm API'ler OpenAPI (Swagger) 3.1 standardında dökümante edilir.
*   **Idempotency:** Tüm mutasyona sebep olan (POST, PUT, PATCH, DELETE) endpoint'ler idempotent olmak zorundadır (Idempotency-Key header'ı zorunludur).

## 02. System Context
Sistem; Core E-Commerce API, Merchant Admin API, B2B Portal API, AI Commerce API ve Webhook Gateway olmak üzere 5 ana giriş noktasına (ingress) sahiptir. Dış sistemlerle (ERP, CRM) entegrasyon sadece Webhook'lar ve Asenkron Queue worker'lar üzerinden yapılır.

## 03. Domain Map
Sistem 4 ana domain grubundan oluşur:
1.  **Core Commerce:** Catalog, Inventory, Pricing.
2.  **Transaction:** Cart, Checkout, Order, Payment.
3.  **Customer & B2B:** Identity, B2B Ledger, CRM.
4.  **Operations:** Fulfillment, Shipping, Notification.

## 04. Bounded Context Map
Her bounded context (BC) kendi veritabanı şemasını (schema) yönetir. (Örn: `catalog_products`, `order_orders`). Ortak (shared) tablo kullanımı kesinlikle yasaktır. Bir BC'nin verisine ihtiyaç duyan diğer BC, ya event dinler ya da API kontratını kullanır.

## 05. Module Dependency Rules
*   Bağımlılık yönü her zaman dıştan içe doğrudur (Onion Architecture).
*   **Application Layer**, **Domain Layer**'a bağımlıdır.
*   Hiçbir modül `Order` modülüne doğrudan bağımlı olamaz (Döngüsel bağımlılığı önlemek için).
*   Cross-cutting concern'ler (Loglama, Auth) altyapı (Infrastructure) katmanında çözülür.

## 06. Tenant / Store / Channel Model
*   **İzolasyon Modeli:** Logical Separation (Shared Database, Separate Schema/Tenant_ID).
*   Veritabanındaki her tabloda zorunlu `tenant_id` kolonu bulunur. Global (tenant-aware olmayan) tablolar hariçtir.
*   Sorgularda Global Scope ile `tenant_id` izolasyonu ORM/Query Builder seviyesinde zorunlu kılınmıştır. Veri sızıntısı (Data Leak) engellenmiştir.
*   Hiyerarşi: `Tenant (Şirket) -> Store (Mağaza) -> Channel (Satış Kanalı: Web, App, B2B) -> Locale & Currency`

## 07. Catalog Domain
Sistemin okuma yükü en yüksek domainidir. Okuma işlemleri CQRS prensibiyle ayrıştırılmalı ve okuma modelleri (Read Models) Redis/Elasticsearch üzerine yansıtılmalıdır (Projection).

## 08. Product Type
Ürünler esnek tiplere (ProductType) sahiptir. Hard-coded ürün özellikleri yasaktır. (Örn: Giyim, Elektronik tipleri).

## 09. Attribute Engine
*   **Dynamic Attributes:** EAV (Entity-Attribute-Value) anti-pattern'inden kaçınılarak, özellikler `JSONB` formatında (PostgreSQL) indexlenebilir şekilde tutulur.
*   Attribute'lar `filterable`, `searchable`, `translatable` gibi metadatalara sahiptir.

## 10. Variant Engine
Varyantlar (SKU), Product'ın fiziksel olarak satılabilir alt birimleridir. Ana ürün satılamaz, varyant satılır. (Varyantsız ürünlerin de gizli bir varsayılan varyantı olur).

## 11. Pricing Engine
*   Fiyatlar sabit değildir; bağlama (Context) göre çözümlenir: `f(Product, Customer, Channel, Currency, Date) = Price`
*   Para birimleri her zaman en küçük birim (Cents/Kuruş) olarak `integer` formatında saklanır. Küsüratlı işlemler (Float/Decimal) YASAKTIR.

## 12. Category / Brand
Nested Set veya Materialized Path modeliyle tutulan, sonsuz derinlikli kategori ağacı.

## 13. Inventory
Stoklar fiziksel depolara (Warehouse) bağlıdır. Stok modeli: `Quantity = Available + Reserved`.

## 14. Reservation
*   **Concurrency Garantisi:** Sepetten checkout'a geçildiğinde stok rezervasyonu (Soft Allocation) başlar.
*   Rezervasyon süresi (Örn: 15 dk) dolduğunda veya ödeme başarısız olduğunda, Cron/Queue worker ile rezervasyon otomatik düşürülür ve `Available` stoğa eklenir.

## 15. Warehouse & 16. Stock Ledger
*   Birden çok depo desteği.
*   Stok hareketleri değişmez bir muhasebe defteri (Ledger) gibi Event Sourcing mantığıyla tutulur (IN/OUT hareketleri). Mevcut stok, bu hareketlerin toplamıdır.

## 17. Customer
Müşteri verileri GDPR/KVKK uyumlu, şifrelenmiş (PII data encryption) olarak tutulur.

## 18. B2B & 19. Credit Ledger
*   Şirket profilleri, yetkili personeller ve hiyerarşik onay mekanizmaları.
*   **Credit Ledger:** Açık hesap işlemleri finansal bir ledger olarak tutulur. Kredi limiti aşımı (Overdraft) sıkı transactional kilitlerle korunur.

## 20. Cart
*   Dağıtık önbellekte (Redis) tutulur, siparişe dönüştüğünde kalıcılaştırılır.
*   Fiyat ve stok her sepete erişimde (veya sepete eklemede) canlı olarak re-validate edilir.

## 21. Checkout
State Machine ile yönetilen ödeme akışı: `Address -> Shipping -> Payment -> Placed`. Her adım tamamlanmadan bir sonrakine geçilemez.

## 22. Order
*   Sipariş durumu katı bir State Machine ile yönetilir. (Örn: `Pending -> Authorized -> Processing -> Shipped -> Delivered`).
*   Durum geçişleri yetkilendirme ve kurallara (Transition Guards) tabidir.

## 23. Payment & 24. Payment Gateway SDK
*   **Gateway Contract:** Sağlayıcı bağımsız (Stripe, Iyzico, vb.) ortak bir `PaymentGatewayInterface` zorunludur.
*   Payment Intent / Attempt mekanizması.

## 25. Refund & 26. Reconciliation
*   Kısmi iade (Partial Refund) desteği.
*   Banka mutabakatı (Reconciliation) için transaction kayıtlarında External Provider Reference ID tutulması zorunludur.

## 27. Shipping & 28. Tax
*   Dinamik kargo fiyatlandırması (Ağırlık, Desi, Bölge).
*   Bölgesel (Zip Code bazlı) ve ürün tipine göre değişen vergi oranları hesaplama motoru.

## 29. Invoice
Fatura yasal bir dökümandır, oluşturulduktan sonra değiştirilemez (Immutable). Sadece iptal (Cancellation) veya iade faturası (Credit Note) kesilebilir.

## 30. RMA / Returns
İade talepleri siparişten ayrı bir yaşam döngüsüne (State Machine) sahiptir: `Requested -> Approved -> Received -> Inspected -> Refunded`.

## 31. Promotion & 32. Rule Engine
*   Güvenli DSL (Domain Specific Language) tabanlı Kural Motoru.
*   AST (Abstract Syntax Tree) ile ifade edilen koşullar: `If (Cart.Total > 100 AND Customer.Group == VIP) Then (Apply Discount(10%))`.
*   Sonsuz döngüleri ve çatışmaları önlemek için promosyon öncelik (Priority) ve durdurma (Stop processing other rules) kuralları.

## 33. Coupon
Tek kullanımlık, çoklu kullanımlık veya kullanıcıya özel şifrelenmiş kodlar. Concurrency (aynı kodu aynı anda kullanma) için Row-Level Locking uygulanır.

## 34. Subscription & 35. Recurring Payment
Abonelik planları ve döngüsel ödeme (Cron) tetikleyicileri. Dunning (Ödeme alınamadığında yeniden deneme) state machine'i.

## 36. CMS & 37. Media
Headless sayfa blokları ve S3/CDN entegre medya yönetimi. Resimler yükleme anında optimize edilir (WebP/AVIF).

## 38. Localization & 39. Translation
Sistemin her string değeri ve ürün datası i18n uyumludur. Fallback dil mekanizması.

## 40. SEO, 41. Schema.org, 42. Sitemap
*   SEO Entity Modeli: Her entity (Product, Category) URL Rewrite, Meta Title, Description içerir.
*   `hreflang` ve `canonical` URL kuralları kesin olarak uygulanır.
*   Otomatik JSON-LD şemaları oluşturulur.

## 43. Merchant Center
Google Merchant Center / Meta XML feed'leri oluşturma ve streaming (büyük veriler için chunked iterasyon) yeteneği.

## 44. Search
Elasticsearch / Meilisearch altyapısı. Yazım düzeltme (Typo tolerance), facet (filtreleme) ve boosting (ağırlıklandırma) motoru.

## 45. AI Commerce API
*   AI Ajanları için güvenli, salt-okunur ağırlıklı "Function Calling" (Tools) API'si.
*   Prompt Injection ve yetkisiz veri erişimine karşı (Kullanıcı X sadece kendi siparişlerini görebilir) katı IAM/RBAC denetimi.

## 46. Authentication & 47. RBAC
*   Oauth2 veya JWT tabanlı Stateless Authentication.
*   Role-Based Access Control (RBAC) + Attribute-Based Access Control (ABAC). "Ürün Sorumlusu sadece X kategorisindeki ürünleri düzenleyebilir" yeteneği.

## 48. API Security
*   Rate Limiting (IP ve Token bazlı).
*   Payload şifreleme ve imzalama (Webhooklar için HMAC SHA256).

## 49. Webhooks & 50. Events
*   **Idempotency & Replay:** Webhook teslimatları kaydedilir, başarısız olanlar Exponential Backoff ile tekrar denenir.
*   Event'ler standart CloudEvents spesifikasyonunda formatlanır.

## 51. Outbox & 52. Queues
*   **Transactional Outbox Pattern:** Veritabanına yazma işlemi ile event fırlatma işleminin (Dual Write) tutarlılığını garanti eder. Event önce aynı transaction içinde `outbox` tablosuna yazılır, sonra asenkron worker tarafından Message Broker'a (RabbitMQ/Kafka/Redis) iletilir.

## 53. Plugin Architecture & 54. Integration SDK
Uygulama çekirdeği (Core) değiştirilmeden, Service Container binding'leri ve Event Listener'lar üzerinden eklenti geliştirme (Hook sistemi).

## 55. Admin Architecture
React/Vue SPA tabanlı, tamamen Headless API tüketen yönetim arayüzü.

## 56. Frontend Contract & 57. Mobile Contract
BFF (Backend for Frontend) veya GraphQL üzerinden optimize edilmiş payload'lar.

## 58. Database Architecture & 59. Complete ERD
PostgreSQL tercih edilir. İlişkisel bütünlük (Foreign Keys) sıkı uygulanır. NoSQL kullanılacaksa sadece okuma/cache/döküman (JSON) ihtiyacı olan yerlerde tercih edilir.

## 60. Index Strategy & 61. Cache
*   Tüm foreign key ve sık sorgulanan alanlarda B-Tree index zorunluluğu.
*   Cache Invalidations: Etiket tabanlı (Tag-based) cache temizleme mekanizması (Örn: Ürün değiştiğinde `product:123` etiketli tüm cache'ler düşer).

## 62. Performance & 63. Observability
*   N+1 Sorgu problemi Eager Loading ile sistem seviyesinde engellenir.
*   OpenTelemetry, Tracing (X-Request-ID) ve merkezi loglama (ELK/Grafana Loki).

## 64. Audit & 65. Security Threat Model
Tüm veri mutasyonları `audit_logs` tablosuna (Kim, Ne Zaman, Eski Veri, Yeni Veri) yazılır.

## 66. KVKK / Data Governance
Veri anonimleştirme (Right to be forgotten) api'leri. PII (Personally Identifiable Information) maskeleme.

## 67. Testing Architecture (68-70)
*   Birim Test (Unit Test), Entegrasyon Testi, Uçtan Uca (E2E) Test piramidi zorunludur.
*   **Concurrency & Payment Testing:** Yarış durumları (Race conditions) ve mock ödeme senaryoları (Başarılı, 3D Secure, Yetersiz Bakiye, Timeout) otomatik testlerle doğrulanır.
*   **Contract Testing:** Frontend ve API arasındaki veri kontratının kırılmadığı test edilir.

## 71. Deployment & 72. CI/CD
*   Zero-Downtime Deployment (Sıfır kesinti).
*   Git flow ve immutable artifact yapısı.

## 73. Backup & 74. Disaster Recovery
Düzenli Snapshot, Point-in-time recovery (PITR) ve aktif-pasif felaket kurtarma planı.

## 75. API Versioning
URL path (`/v1/`) veya Header tabanlı API versiyonlama. Geriye dönük uyumluluk (Backward compatibility) esastır; kırıc değişiklikler (Breaking changes) sadece yeni versiyonda yapılır.

## 76. Migration & 77. Upgrade Strategy
Veritabanı şema değişiklikleri sadece up/down migration dosyaları ile yönetilir. Sürüm yükseltmeleri veri kaybı yaşatmayacak şekilde planlanır.

## 78. ADR Rules & 79. Architecture Governance
Önemli teknik kararlar (Kütüphane seçimi, Mimari değişiklik) Architecture Decision Records (ADR) belgeleri oluşturularak kayıt altına alınır.

## 80. Definition of Done (DoD)
Bir özelliğin bitti kabul edilmesi için: "Kodlandı, Testler yazıldı (Min %80 coverage), OpenAPI güncellendi, Performans testi yapıldı, Code Review alındı." şartları aranır.

## 81. Implementation Phases
Geliştirme, `PROJE_DETAY.md` dosyasındaki 10 Katmanlı (35 Faz) hiyerarşiye birebir uyumlu, Agile (Scrum/Kanban) iterasyonlarla yürütülür.

## 82. Quality Gates & 83. Final Architecture Gate
SonarQube/Benzeri araçlarla statik kod analizi. Mimari onay (Architecture Gate) geçilmeden CI/CD pipeline'ı kodu Production'a almaz.

---
**TANIMLANDI:** Bu belge, Antigravity E-Commerce projesinin teknik anayasasıdır. Yapay Zeka veya Mühendisler tarafından kod üretimi aşamasında referans alınacak tek teknik doğruluk kaynağı (Single Source of Truth) budur.

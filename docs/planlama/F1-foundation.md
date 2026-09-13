# F1 — FOUNDATION (Temel Altyapı)

> **Faz:** 1 — Foundation  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** KRİTİK  
> **Bağımlılık:** F0 tamamlanmış olmalı  
> **İnşaat Analojisi:** Betonarme temel — her şey bunun üstüne oturuyor

---

## Amaç

Projenin teknik temelini atmak: dizin yapısı, framework kurulumu, modüler mimari iskeleti, environment yönetimi, CI/CD temeli.

---

## Görevler

### A. Proje Kurulumu

- [ ] PHP framework kurulumu (ADR-001'e göre belirlenen)
- [ ] Composer bağımlılıkları başlangıç setiyle kuruldu
- [ ] `.env` yapısı tasarlandı ve `.env.example` oluşturuldu
- [ ] Git repository başlatıldı
- [ ] `.gitignore` düzenlendi (secrets, vendor, storage vb.)
- [ ] Git branch stratejisi belirlendi (main / develop / feature / release)

### B. Dizin Yapısı

- [ ] Modular Monolith klasör yapısı oluşturuldu
  ```
  /app
    /Modules
      /Catalog
      /Product
      /Variant
      /Attribute
      /Inventory
      /Warehouse
      /Customer
      /Cart
      /Checkout
      /Order
      /Payment
      /Refund
      /Shipment
      /Invoice
      /Promotion
      /Pricing
      /Tax
      /Content
      /SEO
      /Search
      /Review
      /Notification
      /Analytics
      /Integration
      /Webhook
      /Auth
      /Authorization
      /Audit
      /Media
      /Localization
  /docs
    /adr
    /planlama
    /api
  ```
- [ ] Her modül için standart alt yapı oluşturuldu
  - [ ] `Domain/` (Entities, Value Objects, Repository Interfaces)
  - [ ] `Application/` (Use Cases / Services, DTOs)
  - [ ] `Infrastructure/` (Repository Implementations, External Adapters)
  - [ ] `API/` (Controllers, Resources, Requests)
  - [ ] `Tests/`

### C. Konfigürasyon Yönetimi

- [ ] Environment-based config sistemi kuruldu
- [ ] Config namespace'leri oluşturuldu: store, channel, locale, payment, shipping, tax, seo, email, sms, analytics
- [ ] Typed configuration modeli değerlendirildi
- [ ] Secret management stratejisi belirlendi (env file / vault)
- [ ] Config validation — startup health check sistemi yapıldı

### D. Service Container / Dependency Injection

- [ ] DI container yapılandırıldı
- [ ] Module service providers oluşturuldu
- [ ] Interface → Implementation binding'ler yapılandırıldı

### E. Database Bağlantısı

- [ ] MySQL bağlantısı yapılandırıldı
- [ ] Migration sistemi kuruldu
- [ ] Seeder altyapısı kuruldu
- [ ] Database naming convention standartı uygulandı (Türkçe ASCII)

### F. Development Environment

- [ ] Docker / docker-compose veya Valet/Herd yapılandırıldı
- [ ] Local ortam kurulum dokümantasyonu yazıldı (README.md)
- [ ] Ortam değişkenleri: local / development / staging / production ayrımı
- [ ] Code style / linting araçları (PHP-CS-Fixer, PHPStan) kuruldu

### G. Queue / Job Altyapısı

- [ ] Queue driver seçildi (Redis / Database)
- [ ] Queue connection yapılandırıldı
- [ ] Failed job tablosu oluşturuldu
- [ ] Job retry stratejisi belirlendi

### H. Cache Altyapısı

- [ ] Cache driver seçildi (Redis / File)
- [ ] Cache prefix stratejisi belirlendi
- [ ] Cache invalidation pattern belirlendi

### I. Logging Altyapısı

- [ ] Log channel yapılandırıldı
- [ ] Structured logging formatı belirlendi
- [ ] Log level stratejisi (production vs development)
- [ ] Correlation ID middleware oluşturuldu

### J. Health Check Endpointleri

- [ ] `/health` endpoint oluşturuldu
- [ ] `/ready` endpoint oluşturuldu
- [ ] Database, queue, cache, storage kontrolleri eklendi

### K. Event Bus Backbone (Temel İskelet)

> ⚠️ Tam Event Bus implementasyonu F19'da yapılır.  
> Ancak bu temel iskelet F1'de kurulmalı — tüm modüller buna bağlanacak.

- [ ] `DomainEvent` base class oluşturuldu
- [ ] `EventDispatcher` interface tanımlandı
- [ ] Synchronous event dispatch çalışıyor (en basit implementasyon)
- [ ] Event listener registration mekanizması kuruldu
- [ ] F19'da asynchronous + outbox pattern üzerine eklenecek

### L. Test Altyapısı Temeli (F21 ile tamamlanır)

> ⚠️ Tam test coverage F21'de tamamlanır.  
> Ancak bu altyapı **F1'de** kurulmalı — aksi halde her modül kendi setup'ını tekrar yazar.

- [ ] Test database izolasyonu yapılandırıldı (transaction rollback veya ayrı DB)
- [ ] Base Factory class oluşturuldu (tüm entity factory'leri buradan türeyecek)
- [ ] **Fake Payment Gateway** oluşturuldu (test için gerçek gateway gerekmez)
- [ ] **Fake Email Provider** oluşturuldu (test için gerçek mail gönderilmez)
- [ ] **Fake SMS Provider** oluşturuldu
- [ ] **Fake Push Notification Provider** oluşturuldu
- [ ] Test helper'lar: `actingAs()`, `withFakePayment()`, `withFakeQueue()` vb.
- [ ] Seeder altyapısı kuruldu (gerçekçi test verisi üretir)

### M. Headless SEO — Mimari Kural (ADR)

> Bu kural ADR olarak belgelenmeli ve tüm frontend seçimlerinde referans alınmalı.

- [ ] ADR'de belgelendi: **"Headless frontend SSR/SSG zorunludur"**
  - CSR/SPA (Client-Side Rendering) SEO için kabul edilemez
  - Next.js, Nuxt.js veya benzeri SSR framework şart
  - Statik ürün/kategori sayfaları: SSG (Static Site Generation)
  - Dinamik içerik (sepet, sipariş): SSR veya Client hydration
- [ ] `GET /api/v1/store/seo/{type}/{slug}` → SEO verisi (F25'te implement edilir, endpoint tasarımı burada)
- [ ] `<head>` tag'leri frontend'de API'den gelen verilerle doldurulur, hardcode edilmez

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Framework çalışıyor
- [ ] Modül yapısı oluşturuldu
- [ ] Database bağlantısı çalışıyor
- [ ] Migration sistemi çalışıyor
- [ ] Queue sistemi çalışıyor
- [ ] Cache sistemi çalışıyor
- [ ] Health check endpoint çalışıyor
- [ ] Linting / static analysis çalışıyor
- [ ] README.md kurulum adımları ile tamamlandı
- [ ] Event Bus backbone çalışıyor (sync dispatch)
- [ ] Test altyapısı kuruldu (fake providers + factory + test DB izolasyonu)
- [ ] Headless SSR zorunluluğu ADR olarak belgelendi

---

_Son güncelleme: -_  
_Sorumlu: -_

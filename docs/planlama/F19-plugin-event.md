# F19 — PLUGIN SYSTEM & EVENT BUS

> **Faz:** 19 — Plugin System  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** YÜKSEK  
> **Bağımlılık:** F1 (Foundation)

---

## Amaç

Core platform'u bozmadan yeni özellikler eklenebilmesini sağlayan Plugin mimarisini ve Event Bus sistemini kurmak.

---

## Görevler

### A. Plugin Architecture

- [ ] Plugin manifest yapısı tanımlandı:
  ```json
  {
    "name": "iyzico-payment",
    "version": "1.0.0",
    "description": "...",
    "author": "...",
    "type": "payment-gateway",
    "dependencies": [],
    "permissions": ["payment.process"],
    "configuration": {...},
    "min_platform_version": "1.0.0"
  }
  ```
- [ ] Plugin lifecycle implementasyonu:
  - [ ] install / enable / disable / uninstall
  - [ ] migration desteği (plugin kendi migration'larını çalıştırıyor)
  - [ ] configuration schema
  - [ ] route registration
  - [ ] event listener registration
  - [ ] command registration
  - [ ] admin UI extension point
  - [ ] API extension point
- [ ] Plugin core'u bozmadan çalışıyor (izole)
- [ ] Plugin discovery mekanizması

### B. Plugin Türleri

- [ ] `PaymentGatewayPlugin` — Ödeme sağlayıcıları
- [ ] `ShippingProviderPlugin` — Kargo sağlayıcıları
- [ ] `NotificationChannelPlugin` — Bildirim kanalları (Email, SMS, Push)
- [ ] `AnalyticsPlugin` — Analytics entegrasyonları
- [ ] `SearchDriverPlugin` — Arama motorları
- [ ] `IntegrationPlugin` — CRM, ERP, Marketplace vb.

### C. Domain Event Architecture (Event Bus)

- [ ] Domain Event base class
- [ ] Event versiyonlama
- [ ] Event'lerin `giden_kutusu` (outbox) tablosuna kaydı
- [ ] Event dispatcher implementasyonu
- [ ] Synchronous event listener'lar
- [ ] Asynchronous event listener'lar (queue üzerinden)

### D. Temel Domain Event'leri

**Catalog:**
- [ ] `ProductCreated`, `ProductUpdated`, `ProductPublished`
- [ ] `CategoryCreated`, `CategoryUpdated`

**Inventory:**
- [ ] `InventoryReserved`, `InventoryReleased`, `InventoryCommitted`
- [ ] `LowStockDetected`, `OutOfStock`

**Order:**
- [ ] `OrderCreated`, `OrderConfirmed`, `OrderPaid`
- [ ] `OrderShipped`, `OrderDelivered`, `OrderCompleted`
- [ ] `OrderCancelled`

**Payment:**
- [ ] `PaymentInitiated`, `PaymentCaptured`, `PaymentFailed`
- [ ] `RefundCreated`, `RefundCompleted`

### E. Outbox Pattern

- [ ] `giden_kutusu` tablosu oluşturuldu
- [ ] Database transaction + event publish consistency sağlandı
- [ ] Outbox worker (event'leri publish eden background job)
- [ ] At-least-once delivery garantisi
- [ ] İdempotent consumer pattern

### F. Queue / Background Job Altyapısı

- [ ] Ağır işlemler queue'ya gönderiliyor:
  - [ ] Email gönderimi
  - [ ] Fatura PDF üretimi
  - [ ] Görsel işleme
  - [ ] Merchant feed üretimi
  - [ ] Search index güncelleme
  - [ ] Webhook retry
  - [ ] Bildirimler
  - [ ] Raporlar
  - [ ] Bulk import
- [ ] Job progress tracking (admin panel görünür)
- [ ] Failed job dead letter queue
- [ ] Job retry stratejisi (exponential backoff)

### G. Webhook System (Platform → External)

- [ ] Outbound webhook architecture
- [ ] Webhook subscription yönetimi (admin panel)
- [ ] Webhook event türleri (kullanıcı seçebilir)
- [ ] Webhook delivery: retry, signature, idempotency, event version
- [ ] Webhook delivery log (`webhook_teslimleri` tablosu)
- [ ] Dead letter handling

### H. Cron / Scheduler

- [ ] Zamanlanmış job'lar:
  - [ ] Reservation expiry kontrolü
  - [ ] Kampanya başlatma/bitiş
  - [ ] Feed üretimi
  - [ ] Sitemap güncelleme
  - [ ] Temizlik (expired sessions, tokens)
  - [ ] Raporlar
- [ ] Job distributed locking (aynı job 2 kez çalışmasın)

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Plugin install/enable/disable çalışıyor
- [ ] Event bus çalışıyor
- [ ] Outbox pattern çalışıyor
- [ ] Queue sistemi çalışıyor
- [ ] Cron scheduler çalışıyor
- [ ] Webhook delivery çalışıyor
- [ ] Payment ve Shipping plugin'leri plugin architecture üzerinde test edildi
- [ ] Unit ve integration testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

# F18 — API ARCHITECTURE

> **Faz:** 18 — API  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** KRİTİK — Tüm fazlarda paralel yürütülür  
> **Bağımlılık:** F1 (Foundation)

---

## Amaç

Versiyonlu, idempotent, rate-limited, standart yanıt formatlı bir API katmanı oluşturmak. Bu faz diğer tüm fazlarla paralel yürütülür — her domain kendi API endpoint'lerini bu standartta yazar.

---

## Görevler

### A. API Versiyonlama

- [ ] URL bazlı versiyonlama: `/api/v1/...`
- [ ] Breaking change yönetim stratejisi (v1 → v2 geçişi)
- [ ] Backward compatibility politikası belirlendi
- [ ] Deprecated endpoint'ler için sunset header

### B. Standart API Yanıt Formatı

- [ ] Başarılı yanıt formatı:
  ```json
  {
    "data": {...},
    "meta": {"page": 1, "total": 100, "per_page": 15},
    "links": {"self": "...", "next": "..."}
  }
  ```
- [ ] Hata yanıt formatı:
  ```json
  {
    "message": "...",
    "errors": {...},
    "code": "PAYMENT_FAILED",
    "trace_id": "..."
  }
  ```
- [ ] Error code enumu (PAYMENT_FAILED, OUT_OF_STOCK, INVALID_COUPON, ORDER_NOT_FOUND vb.)
- [ ] HTTP status code kullanım standardı

### C. API Endpoint Sınıflandırması

- [ ] Public endpointler (auth gerekmez)
- [ ] Authenticated endpointler (customer token)
- [ ] Admin endpointler (admin token + RBAC)
- [ ] Internal endpointler (servisler arası)
- [ ] Webhook endpointler (signature)
- [ ] Her endpoint için authorization policy tanımlandı

### D. Admin vs Storefront API Ayrımı

- [ ] `/api/v1/admin/...` — Admin operasyonları
- [ ] `/api/v1/store/...` — Storefront operasyonları
- [ ] Gelecek için: `/api/v1/agent/...` — AI Agent API (scope kısıtlı)

### E. Idempotency

- [ ] `Idempotency-Key` header implementasyonu
- [ ] `idempotency_anahtarlari` tablosunda saklama
- [ ] Idempotency scope: endpoint + key
- [ ] Geçerlilik süresi belirlendi
- [ ] Kapsanan endpoint'ler: checkout/order creation, payment, refund

### F. Rate Limiting

- [ ] Endpoint bazlı rate limit yapılandırması:
  - [ ] Login: IP başına 5/dakika
  - [ ] Register: IP başına 10/dakika
  - [ ] Checkout: kullanıcı başına 5/dakika
  - [ ] Payment: kullanıcı başına 3/dakika
  - [ ] Password reset: 3/saat
  - [ ] Search: 100/dakika
  - [ ] Webhook: 1000/dakika
- [ ] Rate limit yanıtı: `429 Too Many Requests` + `Retry-After` header
- [ ] IP / User / Tenant scope'ları

### G. Pagination

- [ ] Offset pagination (standart liste endpoint'leri)
- [ ] Cursor pagination (event log, audit log gibi sıralı stream'ler)
- [ ] Unbounded pagination engellendi (max page size)
- [ ] Standart meta formatı: `page`, `per_page`, `total`, `last_page`

### H. DTO / Resource Layer

- [ ] Database entity'leri doğrudan API response olarak dönmüyor
- [ ] Her domain için Resource/Transformer class'ı
- [ ] Internal fields API'ye sızmamalı
- [ ] Null value yönetimi standardı

### I. HTTP Cache Headers

- [ ] `ETag` / `Last-Modified` implementasyonu (read-heavy endpoint'ler)
- [ ] `Cache-Control` stratejisi
- [ ] API response cache invalidation

### J. OpenAPI / Swagger

- [ ] OpenAPI 3.x specification oluşturuldu
- [ ] Tüm endpoint'ler dokümante edildi
- [ ] API documentation otomatik üretimi
- [ ] `docs/api/` altında yayımlandı

### K. Security Headers

- [ ] `Content-Security-Policy`
- [ ] `X-Content-Type-Options: nosniff`
- [ ] `Referrer-Policy`
- [ ] `HSTS`
- [ ] CSP'nin 3DS/payment iframe gereksinimleriyle uyumu

### L. AI Agent API (Gelecek Hazırlığı)

- [ ] `/api/v1/agent/` namespace ayrıldı
- [ ] Action-level authorization belirlendi
- [ ] Explicit confirmation mekanizması planlandı
- [ ] Scope kısıtlı token (product search, cart, order status — ödeme değil)

---

## Çıkış Kriteri (Exit Criteria)

- [ ] API versiyonlama çalışıyor
- [ ] Standart yanıt formatı tutarlı kullanılıyor
- [ ] Rate limiting çalışıyor
- [ ] Idempotency çalışıyor
- [ ] OpenAPI dokümantasyonu oluşturuldu
- [ ] Security header'lar eklendi
- [ ] Contract testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

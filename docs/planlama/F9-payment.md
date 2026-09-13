# F9 — PAYMENT (Ödeme) ARCHITECTURE

> **Faz:** 9 — Payment  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** KRİTİK  
> **Bağımlılık:** F8 (Checkout), F3 (Auth)

---

## Amaç

Genişletilebilir, plugin-tabanlı ödeme mimarisini oluşturmak. Hiçbir ödeme sağlayıcısına doğrudan bağımlı olmayan, yeni sağlayıcıların core kod değiştirilmeden eklenebileceği bir yapı kurulacaktır.

---

## ⚠️ Kritik Kurallar

> - Tek bir ödeme sağlayıcısına bağımlı mimari **KESİNLİKLE** kabul edilmez  
> - Browser redirect'e güvenme — **Webhook-first** architecture  
> - Kart bilgileri core sistemde **SAKLANMAZ**  
> - PCI DSS kapsamı minimize edilir  
> - Payment status ≠ Order status (bağımsız state machine)

---

## Görevler

### A. Payment Gateway Interface / Contract

- [ ] `PaymentGatewayInterface` tasarlandı:
  - [ ] `createPayment()`
  - [ ] `authorize()`
  - [ ] `capture()`
  - [ ] `void()`
  - [ ] `refund()`
  - [ ] `getPaymentStatus()`
  - [ ] `handleWebhook()`
- [ ] Capability-based payment architecture:
  - [ ] `supportsRefund()`
  - [ ] `supportsPartialRefund()`
  - [ ] `supports3DS()`
  - [ ] `supportsInstallments()`
  - [ ] `supportsTokenization()`
  - [ ] `supportsRecurring()`
  - [ ] `supportsCapture()`
  - [ ] `supportsVoid()`

### B. Payment State Machine

- [ ] Payment state machine implementasyonu:
  ```
  pending → authorized → captured → refunded
         → failed
         → cancelled
         → partially_refunded
  ```
- [ ] Geçersiz transition engellendi

### C. Gateway Implementasyonları (Plugin Mimarisi)

- [ ] iyzico gateway plugin:
  - [ ] Configuration (API key, secret, sandbox/live)
  - [ ] 3D Secure akışı
  - [ ] Webhook handler
  - [ ] Refund
  - [ ] Status mapping
  - [ ] Test senaryoları
- [ ] PayTR gateway plugin
- [ ] Banka havalesi / EFT (offline) gateway:
  - [ ] `pending → awaiting_confirmation → confirmed/rejected` akışı
  - [ ] Admin manuel onay arayüzü

### D. Webhook Mimarisi

- [ ] Webhook endpoint her gateway için ayrı: `/api/v1/webhooks/{provider}`
- [ ] Signature verification (provider bazında)
- [ ] Idempotency (aynı webhook event ID tekrar işlenmiyor)
- [ ] Replay protection
- [ ] Event logging (`webhook_olaylari` tablosu)
- [ ] Retry mekanizması (failed webhook için)
- [ ] Dead letter queue

### E. 3D Secure

- [ ] Browser redirect, iframe, hosted checkout, direct API akışları abstraction üzerinden desteklendi
- [ ] 3DS başarı/başarısızlık akışları test edildi
- [ ] CSP uyumluluğu değerlendirildi

### F. Refund Sistemi

- [ ] Full refund implementasyonu
- [ ] Partial refund implementasyonu (item bazlı)
- [ ] Gateway partial refund desteklemiyorsa fallback stratejisi
- [ ] Refund event: `RefundCreated`, `RefundCompleted`

### G. Multi Payment Method

- [ ] Bir mağaza aynı anda birden fazla ödeme yöntemi aktif edebilir
- [ ] Her ödeme yöntemi için: store / channel / currency / country / customer group bazlı aktif/pasif
- [ ] Admin panel: ödeme yöntemi yönetimi
- [ ] Secret değerleri masked gösterim

### H. Installments (Taksit)

- [ ] Taksit seçenekleri gateway'den dinamik alınıyor
- [ ] Taksit sayısı × taksit tutarı hesaplaması
- [ ] Taksit komisyon oranı yönetimi

### I. Admin API

- [ ] `GET /api/v1/admin/odeme-yontemleri` — Ödeme yöntemi listesi
- [ ] `POST /api/v1/admin/odeme-yontemleri` — Yeni ödeme yöntemi ekle
- [ ] `PUT /api/v1/admin/odeme-yontemleri/{id}` — Güncelle
- [ ] `GET /api/v1/admin/odemeler` — Ödeme listesi
- [ ] `GET /api/v1/admin/odemeler/{id}` — Ödeme detayı
- [ ] `POST /api/v1/admin/odemeler/{id}/iade` — İade başlat
- [ ] `POST /api/v1/admin/odemeler/{id}/onayla` — Banka havalesi onayı
- [ ] `GET /api/v1/admin/webhook-olaylari` — Webhook log

### J. Reconciliation

- [ ] Payment provider ile platform arasında reconciliation servisi
- [ ] Provider: PAID / Platform: PENDING → uyarı
- [ ] Günlük reconciliation job

### K. Test

- [ ] Fake gateway oluşturuldu (test ortamı için)
- [ ] Test senaryoları:
  - [ ] success
  - [ ] failure
  - [ ] timeout
  - [ ] 3DS success / failure
  - [ ] duplicate webhook
  - [ ] late webhook
  - [ ] full refund
  - [ ] partial refund

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Payment Gateway Interface implementasyonu var
- [ ] En az 1 gerçek gateway (iyzico veya PayTR) çalışıyor
- [ ] Banka havalesi çalışıyor
- [ ] Webhook signature verification çalışıyor
- [ ] Idempotency çalışıyor
- [ ] Payment state machine çalışıyor
- [ ] Refund çalışıyor
- [ ] Tüm test senaryoları geçiyor
- [ ] Kart bilgisi sistem içinde saklanmıyor

---

_Son güncelleme: -_  
_Sorumlu: -_

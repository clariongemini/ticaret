# F10 — ORDER DOMAIN

> **Faz:** 10 — Order  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** KRİTİK  
> **Bağımlılık:** F8 (Checkout), F9 (Payment), F6 (Inventory)

---

## Amaç

Sipariş domain'ini immutable historical record olarak tasarlamak. Snapshot mimarisi, state machine ve order lifecycle'ı bu fazda kurulacaktır.

---

## ⚠️ Kritik Kurallar

> - Sipariş immutable historical record olarak tasarlanır  
> - Ürün sonradan adını/fiyatını değiştirse bile eski sipariş DEĞİŞMEZ  
> - Internal ID ile customer-facing order number AYRILMALIDIR  
> - Siparişler fiziksel olarak silinmez

---

## Görevler

### A. Order Entity (Snapshot Mimarisi)

- [ ] `Order` entity oluşturuldu
- [ ] Order snapshot alanları:
  - [ ] `musteri_adi_anlık` (name snapshot)
  - [ ] `fatura_adresi_anlık` (billing address snapshot)
  - [ ] `teslimat_adresi_anlık` (shipping address snapshot)
  - [ ] `para_birimi_anlık`
  - [ ] `doviz_kuru_anlık`
  - [ ] `kargo_yontemi_anlık`
  - [ ] `kargo_ucret_anlık`
  - [ ] `vergi_tutari_anlık`
  - [ ] `indirim_tutari_anlık`
  - [ ] `ara_toplam_anlık`
  - [ ] `genel_toplam_anlık`

### B. Order Item (Kalem) Entity

- [ ] `OrderItem` entity oluşturuldu
- [ ] OrderItem snapshot alanları:
  - [ ] `urun_id` (referans — kayıt silindi diye sipariş bozulmasın)
  - [ ] `varyant_id`
  - [ ] `sku_anlık` (SKU snapshot)
  - [ ] `urun_adi_anlık` (product name snapshot)
  - [ ] `varyant_adi_anlık` (variant name snapshot)
  - [ ] `fiyat_anlık` (price snapshot)
  - [ ] `vergi_anlık` (tax snapshot)
  - [ ] `indirim_anlık` (discount snapshot)
  - [ ] `adet`
  - [ ] `ara_toplam_anlık`

### C. Order Number

- [ ] Internal ID (ULID/UUID) ayrı tutuldu
- [ ] Customer-facing order number: `SIP-2026-000001` formatı
- [ ] Order number guessing riski değerlendirildi (randomize edilen kısım)

### D. Order State Machine

- [ ] Order state machine implementasyonu:
  ```
  beklemede (pending)
  → onaylandi (confirmed)
  → hazirlaniyor (processing)
  → paketlendi (packed)
  → kargoya_verildi (shipped)
  → teslim_edildi (delivered)
  → tamamlandi (completed)
  → iptal_edildi (cancelled)
  → iade_edildi (returned)
  ```
- [ ] Geçersiz state transition engellendi
- [ ] State geçiş geçmişi `siparis_durum_gecmisi` tablosuna kaydediliyor
- [ ] Her state değişikliği event fırlatıyor

### E. Order Domain Servisleri

- [ ] `OrderCreationService` — checkout'tan sipariş oluşturma
- [ ] `OrderCancellationService` — iptal + stok serbest bırakma
- [ ] `OrderFulfillmentService` — hazırlama/paketleme akışı
- [ ] `OrderRefundService` — iade + payment refund koordinasyonu

### F. Admin API

- [ ] `GET /api/v1/admin/siparisler` — Sipariş listesi (filtreli, sayfalı)
- [ ] `GET /api/v1/admin/siparisler/{id}` — Sipariş detayı (tam bilgi)
- [ ] `PUT /api/v1/admin/siparisler/{id}/durum` — Durum güncelle
- [ ] `POST /api/v1/admin/siparisler/{id}/iptal` — Sipariş iptal
- [ ] `POST /api/v1/admin/siparisler/{id}/not` — Not ekle
- [ ] `GET /api/v1/admin/siparisler/{id}/gecmis` — Durum geçmişi
- [ ] `GET /api/v1/admin/siparisler/istatistik` — Dashboard özet

### G. Storefront API

- [ ] `GET /api/v1/store/siparislerim` — Müşterinin siparişleri
- [ ] `GET /api/v1/store/siparislerim/{number}` — Sipariş detayı
- [ ] `POST /api/v1/store/siparislerim/{number}/iptal` — Sipariş iptal talebi

### H. Event'ler

- [ ] `OrderCreated`
- [ ] `OrderConfirmed`
- [ ] `OrderPaid`
- [ ] `OrderShipped`
- [ ] `OrderDelivered`
- [ ] `OrderCompleted`
- [ ] `OrderCancelled`

### I. Customer Data Isolation (Güvenlik)

- [ ] Müşteri yalnızca kendi siparişlerini görebiliyor
- [ ] IDOR kontrolü: `/siparislerim/{id}` başka müşterinin siparişini döndüremiyor
- [ ] IDOR testleri yazıldı

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Sipariş oluşturma çalışıyor (snapshot'larla)
- [ ] Order number sistemi çalışıyor
- [ ] State machine çalışıyor
- [ ] State geçiş geçmişi kaydediliyor
- [ ] Customer data isolation doğrulandı
- [ ] IDOR testleri geçiyor
- [ ] Unit, integration ve feature testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

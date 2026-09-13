# F11 — SHIPPING (Kargo) DOMAIN

> **Faz:** 11 — Shipping  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** YÜKSEK  
> **Bağımlılık:** F10 (Order), F5 (Product — ağırlık/boyut)

---

## Amaç

Payment sistemi gibi plugin mimarisiyle genişletilebilen kargo sistemi kurmak. Kural motoru ve çoklu sağlayıcı desteği bu fazın temelini oluşturur.

---

## Görevler

### A. Shipping Gateway Interface

- [ ] `ShippingProviderInterface` tasarlandı:
  - [ ] `getRates()` — Kargo seçenekleri ve ücret listesi
  - [ ] `createShipment()` — Gönderi oluştur
  - [ ] `getLabel()` — Kargo etiketi
  - [ ] `trackShipment()` — Takip bilgisi
  - [ ] `cancelShipment()` — İptal
  - [ ] `getStatus()` — Durum eşleştirme
- [ ] Capability interface:
  - [ ] `supportsTracking()`
  - [ ] `supportsLabel()`
  - [ ] `supportsCancel()`
  - [ ] `supportsRateCalculation()`

### B. Shipping Provider Plugin'leri

- [ ] Yurtiçi Kargo plugin
- [ ] MNG / DHL eCommerce plugin
- [ ] Aras Kargo plugin
- [ ] Manuel / flat-rate kargo (basit sabit ücret)
- [ ] Ücretsiz kargo seçeneği

### C. Shipping Rule Engine

- [ ] Kural motoru implementasyonu
- [ ] Desteklenen kural kriterleri:
  - [ ] Ürün ağırlığı
  - [ ] Desi (volumetric weight)
  - [ ] Toplam sepet tutarı
  - [ ] Şehir / ilçe
  - [ ] Ülke
  - [ ] Ürün kategorisi
  - [ ] Ücretsiz kargo limiti (örn. 500 TL üzeri ücretsiz)
  - [ ] Müşteri grubu
- [ ] Admin panel'den kural yönetimi
- [ ] Kural öncelik sırası

### D. Shipment State Machine

- [ ] Shipment state machine:
  ```
  hazirlanıyor (preparing)
  → kargoya_verildi (dispatched)
  → transfer (in_transit)
  → dagitimda (out_for_delivery)
  → teslim_edildi (delivered)
  → teslim_edilemedi (failed_delivery)
  → iade_edildi (returned)
  ```

### E. Tracking

- [ ] Kargo takip numarası kaydı
- [ ] Harici provider'dan takip bilgisi çekme
- [ ] Takip geçmişi log'u
- [ ] Otomatik kargo durumu güncelleme (webhook veya polling)

### F. Admin API

- [ ] `GET /api/v1/admin/kargo-saglayicilari` — Kargo sağlayıcı listesi
- [ ] `POST /api/v1/admin/kargo-saglayicilari` — Yeni sağlayıcı
- [ ] `GET /api/v1/admin/kargo-kurallari` — Kargo kuralları
- [ ] `POST /api/v1/admin/kargo-kurallari` — Kural oluştur
- [ ] `GET /api/v1/admin/gonderi-talepleri` — Gönderi listesi
- [ ] `GET /api/v1/admin/gonderi-talepleri/{id}/takip` — Takip bilgisi
- [ ] `POST /api/v1/admin/gonderi-talepleri/{id}/iptal` — İptal

### G. Storefront API

- [ ] Checkout'ta kargo seçenekleri: `/api/v1/store/checkout/{token}/kargo-secenekleri`
- [ ] `GET /api/v1/store/siparislerim/{id}/kargo` — Kargo takip

### H. Returns (İade) — RMA

- [ ] `RMARequest` entity oluşturuldu
- [ ] RMA state machine:
  ```
  talep_edildi (requested)
  → onaylandi (approved)
  → reddedildi (rejected)
  → urun_alindi (received)
  → incelendi (inspected)
  → iade_edildi (refunded)
  → tamamlandi (completed)
  ```
- [ ] İade ürün inventory'ye giriş durumları: available / damaged / quarantine

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Shipping Gateway Interface implementasyonu var
- [ ] En az 1 kargo provider çalışıyor
- [ ] Kargo kural motoru çalışıyor
- [ ] Ücretsiz kargo limiti çalışıyor
- [ ] Shipment state machine çalışıyor
- [ ] RMA sistemi çalışıyor
- [ ] Unit ve integration testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

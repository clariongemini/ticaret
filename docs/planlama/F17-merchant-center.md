# F17 — GOOGLE MERCHANT CENTER ENTEGRASYONU

> **Faz:** 17 — Merchant Center  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** YÜKSEK  
> **Bağımlılık:** F5 (Product), F9 (Payment), F11 (Shipping), F16 (SEO)

---

## Amaç

Google Merchant Center entegrasyonunu sistemin birinci sınıf modülü olarak kurmak. Ürün feed'i veritabanıyla tutarlı olacak ve otomatik güncellenecektir.

---

## ⚠️ Kritik Kurallar

> - Database, Product page, JSON-LD ve Merchant Feed'deki fiyat/stok **AYNI OLMALI**  
> - Feed ayrı bir ürün verisi DEĞİL — catalog'dan projection  
> - Feed tutarsızlığı Google Merchant Center onaysızlığına yol açar

---

## Görevler

### A. Product Feed Mimarisi

- [ ] Feed'in merkezi catalog'dan üretildiği doğrulandı (ayrı veri yok)
- [ ] Feed alanları:
  - [ ] `id` (SKU veya variant ID)
  - [ ] `title`
  - [ ] `description`
  - [ ] `link` (ürün URL'si)
  - [ ] `image_link`
  - [ ] `additional_image_link`
  - [ ] `availability` (in stock / out of stock / preorder)
  - [ ] `price` (para birimi dahil: "999.00 TRY")
  - [ ] `sale_price`
  - [ ] `sale_price_effective_date`
  - [ ] `brand`
  - [ ] `gtin`
  - [ ] `mpn`
  - [ ] `condition` (new / used / refurbished)
  - [ ] `google_product_category` (Google taxonomy ID)
  - [ ] `product_type`
  - [ ] `shipping`
  - [ ] `shipping_weight`
  - [ ] `identifier_exists`
  - [ ] `color`, `size`, `material` (varyant attribute'lardan)
  - [ ] `item_group_id` (parent ürün ID)

### B. Feed Formatları

- [ ] XML feed üretimi (standart Google Shopping XML)
- [ ] CSV feed üretimi
- [ ] Google Content API / Merchant API entegrasyonu [TO VERIFY — güncel API]
- [ ] Feed URL: `/feeds/urunler-{lang}.xml` (public, erişilebilir)

### C. Feed Üretimi

- [ ] Queue üzerinden feed üretimi (arka planda)
- [ ] Trigger: ürün/stok/fiyat değişikliğinde otomatik feed yenileme
- [ ] Zamanlanmış feed yenileme (günlük)
- [ ] Incremental vs full feed stratejisi

### D. Veri Tutarlılığı Validation

- [ ] Database ↔ Merchant Feed fiyat tutarlılığı kontrolü
- [ ] Stok durumu tutarlılığı kontrolü
- [ ] Tutarsızlık tespit edildiğinde admin uyarısı
- [ ] Merchant Data Quality score (admin panel'den görüntülenebilir):
  - [ ] price eksik
  - [ ] availability eksik
  - [ ] brand eksik
  - [ ] GTIN/MPN eksik
  - [ ] image eksik
  - [ ] shipping bilgisi eksik
  - [ ] return policy eksik

### E. Merchant Center Bağlantısı

- [ ] Merchant Center hesap ID yönetimi (admin panel)
- [ ] API credentials management (masked)
- [ ] Hesap doğrulama endpoint'i
- [ ] Feed submit ve status takibi

### F. Return Policy

- [ ] Mağaza iade politikası yapılandırması
- [ ] Feed'e iade politikası bilgisi ekleniyor
- [ ] `hasMerchantReturnPolicy` schema ile uyum

### G. Admin API

- [ ] `GET /api/v1/admin/merchant/ayarlar` — Merchant ayarları
- [ ] `PUT /api/v1/admin/merchant/ayarlar` — Güncelle
- [ ] `POST /api/v1/admin/merchant/feed-uret` — Manuel feed üret
- [ ] `GET /api/v1/admin/merchant/feed-durum` — Feed son üretim durumu
- [ ] `GET /api/v1/admin/merchant/veri-kalitesi` — Merchant data quality raporu

---

## Çıkış Kriteri (Exit Criteria)

- [ ] XML feed üretiliyor ve doğru format
- [ ] Feed veritabanıyla fiyat/stok tutarlı
- [ ] Merchant Data Quality skoru çalışıyor
- [ ] Feed otomatik güncelleniyor (product/stock/price değişiminde)
- [ ] Google Merchant Center hesap bağlantısı test edildi
- [ ] Integration testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

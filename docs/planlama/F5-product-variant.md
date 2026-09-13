# F5 — PRODUCT & VARIANT & ATTRIBUTE DOMAIN

> **Faz:** 5 — Product / Variant  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** KRİTİK  
> **Bağımlılık:** F4 (Catalog), F2 (Database)

---

## Amaç

Sistemin en kritik modülü olan Product Model'i oluşturmak. Dinamik attribute sistemi, varyant mimarisi ve product type yapısı bu fazda kurulacaktır.

---

## Görevler

### A. Product Type Sistemi

- [ ] Product Type entity oluşturuldu
- [ ] Desteklenen tipler tanımlandı:
  - [ ] `fiziksel` (physical)
  - [ ] `dijital` (digital)
  - [ ] `hizmet` (service)
  - [ ] `abonelik` (subscription)
  - [ ] `paket` (bundle)
  - [ ] `yapilandırılabilir` (configurable)
  - [ ] `özelleştirilebilir` (customizable)
- [ ] Her Product Type için: attribute set, inventory behavior, pricing behavior, shipping behavior, tax behavior tanımlandı
- [ ] Yeni Product Type eklemek için core kod değiştirme gerekmediği doğrulandı

### B. Attribute Sistemi (Dinamik)

- [ ] Attribute Group entity oluşturuldu
- [ ] Attribute Definition entity oluşturuldu
- [ ] Attribute tipleri implementasyonu:
  - [ ] `text`
  - [ ] `textarea`
  - [ ] `number`
  - [ ] `integer`
  - [ ] `decimal`
  - [ ] `boolean`
  - [ ] `select`
  - [ ] `multiselect`
  - [ ] `color`
  - [ ] `date`
  - [ ] `datetime`
  - [ ] `measurement`
  - [ ] `currency`
  - [ ] `file`
  - [ ] `image`
  - [ ] `reference`
- [ ] Attribute Option entity oluşturuldu (select/multiselect için)
- [ ] Product Type ↔ Attribute ilişkisi kuruldu
- [ ] Bir müşteri yalnızca kendi store'una tanımlı attribute'ları görüyor
- [ ] Admin panel: "Bu product type'da hangi attribute'lar aktif?" sorusu çalışıyor

### C. Product Domain

- [ ] Product entity oluşturuldu
- [ ] Product çevirisi sistemi kuruldu (name, description, short_description)
- [ ] Product ↔ Category ilişkisi kuruldu (many-to-many)
- [ ] Product ↔ Brand ilişkisi kuruldu
- [ ] Product ↔ Media ilişkisi kuruldu
- [ ] Product status sistemi: draft / active / archived / scheduled
- [ ] Product attribute değerleri implementasyonu
- [ ] Product SEO alanları (F16 ile entegre)

### D. Variant Sistemi

- [ ] Variant entity oluşturuldu
- [ ] Her variant'ın kendi alanları:
  - [ ] SKU (benzersiz)
  - [ ] Barcode
  - [ ] Price
  - [ ] Compare at price
  - [ ] Cost price
  - [ ] Weight
  - [ ] Dimensions (length, width, height)
  - [ ] Status
  - [ ] Image
  - [ ] Tax class
  - [ ] Inventory policy
- [ ] Dinamik variant kombinasyonu oluşturma servisi
- [ ] Parent product ile variant ilişkisi doğru kuruldu
- [ ] Variant olmayan ürün desteği (simple product)

### E. Bundle / Kit Ürünler

- [ ] Bundle product entity oluşturuldu
- [ ] Bundle ↔ bileşen ürün ilişkisi kuruldu
- [ ] Bundle stok hesaplama servisi: `min(component stocks)` mantığı
- [ ] Bundle fiyat hesaplama servisi

### F. Admin API

**Ürünler:**
- [ ] `GET /api/v1/admin/urunler` — Liste (filtreli, sayfalı)
- [ ] `POST /api/v1/admin/urunler` — Oluştur
- [ ] `GET /api/v1/admin/urunler/{id}` — Detay (varyantlarla birlikte)
- [ ] `PUT /api/v1/admin/urunler/{id}` — Güncelle
- [ ] `DELETE /api/v1/admin/urunler/{id}` — Sil (soft delete)
- [ ] `POST /api/v1/admin/urunler/{id}/kopyala` — Ürün klonlama
- [ ] `POST /api/v1/admin/urunler/toplu-guncelle` — Bulk update (queue)

**Varyantlar:**
- [ ] `GET /api/v1/admin/urunler/{id}/varyantlar`
- [ ] `POST /api/v1/admin/urunler/{id}/varyantlar`
- [ ] `PUT /api/v1/admin/varyantlar/{id}`
- [ ] `DELETE /api/v1/admin/varyantlar/{id}`

**Attribute:**
- [ ] Attribute group CRUD
- [ ] Attribute definition CRUD
- [ ] Attribute option CRUD

### G. Storefront API

- [ ] `GET /api/v1/store/urunler` — Liste (kategori, marka, fiyat filtresi)
- [ ] `GET /api/v1/store/urunler/{slug}` — Ürün detayı (varyantlarla)
- [ ] `GET /api/v1/store/urunler/{id}/benzer` — Benzer ürünler

### H. Product Quality Score

- [ ] Tamamlanmamış ürün uyarıları:
  - [ ] SKU eksik
  - [ ] Fiyat eksik
  - [ ] Görsel eksik
  - [ ] Açıklama eksik
  - [ ] Marka eksik
  - [ ] GTIN eksik
  - [ ] SEO eksik
- [ ] Admin panelden ürün kalite skoru görüntüleniyor

### I. Event'ler

- [ ] `ProductCreated` event
- [ ] `ProductUpdated` event
- [ ] `ProductPublished` event
- [ ] `VariantCreated` event
- [ ] `VariantUpdated` event
- [ ] `VariantPriceChanged` event

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Ürün CRUD çalışıyor
- [ ] Dinamik attribute sistemi çalışıyor
- [ ] Varyant kombinasyonları dinamik oluşturuluyor
- [ ] Bundle ürün stok hesabı doğru
- [ ] Çok dilli ürün çalışıyor (TR/EN)
- [ ] Ürün kalite skoru gösteriliyor
- [ ] Unit, integration ve feature testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

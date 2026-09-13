# F6 — INVENTORY (Stok) DOMAIN

> **Faz:** 6 — Inventory  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** KRİTİK  
> **Bağımlılık:** F5 (Product/Variant)

---

## Amaç

Sistemin en hassas modüllerinden biri olan stok yönetimini kurmak. Race condition, reservation sistemi ve multi-warehouse desteği bu fazın çekirdeğini oluşturur.

---

## ⚠️ Kritik Uyarılar

> `products.stock` alanı KULLANILMAYACAKTIR.  
> Inventory domain ayrı ve bağımsız olacaktır.  
> Race condition testleri ZORUNLUDUR (stok=1 iken 100 eş zamanlı istek).

---

## Görevler

### A. Temel Inventory Kavramları

- [ ] `Warehouse` (Depo) entity oluşturuldu
- [ ] `StockLocation` (Stok Konumu) entity oluşturuldu
- [ ] `InventoryItem` entity oluşturuldu
- [ ] Stok hesaplama formülü implementasyonu:
  ```
  available_stock = physical_stock - reserved_stock
  ```
- [ ] Stok türleri desteklendi:
  - [ ] `mevcut_stok` (available)
  - [ ] `rezerve_stok` (reserved)
  - [ ] `taahhut_stok` (committed)
  - [ ] `gelen_stok` (incoming)
  - [ ] `hasarli_stok` (damaged)
  - [ ] `iade_stok` (returned)

### B. Multi-Warehouse

- [ ] Bir ürünün birden fazla depoda stoku takip ediliyor
- [ ] Inventory allocation stratejileri implementasyonu:
  - [ ] En yakın depo (nearest warehouse)
  - [ ] Öncelikli depo (priority warehouse)
  - [ ] Stok müsaitliğine göre (stock availability)
- [ ] Depo bazlı stok görüntüleme

### C. Stock Reservation Sistemi

- [ ] `StockReservation` entity oluşturuldu
- [ ] Reservation alanları:
  - [ ] reservation_id
  - [ ] inventory_item_id
  - [ ] quantity
  - [ ] expires_at
  - [ ] status (pending / confirmed / expired / cancelled)
  - [ ] cart_id / order_id ilişkisi
- [ ] Checkout başladığında otomatik reservation oluşturuluyor
- [ ] Reservation timeout (örn. 15 dakika) sonrası stok otomatik serbest bırakılıyor
- [ ] Cron job ile süresi dolmuş reservation'lar temizleniyor

### D. Race Condition Koruması

- [ ] Atomic update stratejisi belirlendi (SELECT FOR UPDATE / optimistic locking)
- [ ] Transaction sınırları doğru belirlendi
- [ ] Negative stock oluşmasının önlenmesi test edildi
- [ ] Database-level CHECK constraint: `mevcut_stok >= 0`

### E. Stok Hareketi Kaydı

- [ ] Her stok değişikliği `stok_hareketleri` tablosuna kayıt ediliyor
- [ ] Hareket türleri:
  - [ ] `satin_alim` (purchase)
  - [ ] `satis` (sale)
  - [ ] `iade` (return)
  - [ ] `düzeltme` (adjustment)
  - [ ] `rezerve` (reservation)
  - [ ] `rezerve_iptal` (reservation release)
  - [ ] `transfer` (warehouse transfer)

### F. Admin API

- [ ] `GET /api/v1/admin/depolar` — Depo listesi
- [ ] `POST /api/v1/admin/depolar` — Depo oluştur
- [ ] `GET /api/v1/admin/stoklar` — Stok listesi (ürün/depo bazlı)
- [ ] `POST /api/v1/admin/stoklar/ayarla` — Stok düzeltmesi
- [ ] `POST /api/v1/admin/stoklar/transfer` — Depo transferi
- [ ] `GET /api/v1/admin/stoklar/hareketler` — Stok hareketi geçmişi
- [ ] `GET /api/v1/admin/stoklar/dusuk` — Düşük stok alarmı

### G. Storefront API

- [ ] `GET /api/v1/store/urunler/{id}/stok` — Stok durumu kontrolü (available/out_of_stock)
- [ ] `GET /api/v1/store/varyantlar/{id}/stok` — Variant stok durumu

### H. Reconciliation

- [ ] `physical - reserved = available` tutarlılığını kontrol eden servis
- [ ] Tutarsızlık tespit edildiğinde admin'e alarm
- [ ] Günlük stok reconciliation job

### I. Event'ler

- [ ] `InventoryReserved` event
- [ ] `InventoryReleased` event
- [ ] `InventoryCommitted` event (sipariş onaylandığında)
- [ ] `InventoryAdjusted` event
- [ ] `LowStockDetected` event

---

## Test Gereksinimleri (Zorunlu)

- [ ] **Concurrency test:** stok=1 iken 100 eş zamanlı satın alma isteği → sadece 1 başarılı
- [ ] Rezervasyon timeout testi
- [ ] Negatif stok oluşmaması testi
- [ ] Multi-warehouse allocation testi

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Stok reservation çalışıyor
- [ ] Race condition koruması var ve test edildi
- [ ] Multi-warehouse stok takibi çalışıyor
- [ ] Stok hareketi geçmişi kayıt ediliyor
- [ ] Reconciliation servisi çalışıyor
- [ ] Tüm concurrency testleri geçiyor

---

_Son güncelleme: -_  
_Sorumlu: -_

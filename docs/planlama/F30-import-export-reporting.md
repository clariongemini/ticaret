# F30 — İMPORT / EXPORT & RAPORLAMA

> **Faz:** 30 — Import/Export & Reporting  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** YÜKSEK  
> **Bağımlılık:** F5 (Product), F6 (Inventory), F10 (Order), F20 (Admin Panel)  
> **Yapıdaki Yeri:** Kat 7 — admin panel hazır, tüm domainler mevcut  
> **İnşaat Analojisi:** Bina teslim listesi ve yıllık bakım raporu — içerde ne var, ne işe yarıyor

---

## Amaç

Yeni müşteri onboardingini kolaylaştırmak (toplu ürün import), mevcut verileri dışarı aktarmak ve yöneticilerin işletme performansını anlayabileceği raporlar üretmek.

---

## ⚠️ Kritik Kurallar

> Import: her zaman **dry-run** önce — veri bozulmadan önce hata görülür.  
> Production DB üzerinde ağır rapor sorgusu çalıştırma → read model / cache kullan.  
> Export'larda kişisel veri varsa KVKK kapsamında yetki kontrolü yapılır.

---

## Görevler

### A. Ürün Import Sistemi

- [ ] Desteklenen formatlar:
  - CSV (Excel uyumlu, UTF-8 BOM)
  - Excel (.xlsx)
  - JSON (API bazlı entegrasyon için)
- [ ] Import akışı:
  1. Dosya yükleme
  2. **Kolon mapping** (hangi sütun hangi alana eşleniyor?)
  3. **Dry-run validation** — hiçbir şey yazılmaz, sadece hatalar gösterilir
  4. Hata raporu: hangi satır, hangi sütun, neden hatalı
  5. Kullanıcı "Devam et" derse → gerçek import başlar (queue üzerinden)
  6. Import tamamlanınca email bildirimi
- [ ] Import edilen alanlar:
  - Temel ürün bilgileri (ad, açıklama, SKU, barkod, fiyat, stok)
  - Kategori (isimden eşleme)
  - Marka
  - Varyantlar (çoklu satır / ayrı sheet)
  - Görseller (URL'den otomatik indirme)
  - SEO alanları
  - Özellikler (attribute değerleri)
- [ ] **Duplicate detection:** Aynı SKU tekrar gelirse: Atla / Güncelle / Hata (seçilebilir)
- [ ] **Rollback stratejisi:** Import başarısız olursa neye kadar geri alınacak?
- [ ] Import geçmişi: ne zaman, kim, kaç kayıt, kaç hata

---

### B. Stok Import

- [ ] CSV ile toplu stok güncelleme:
  - SKU + Depo + Yeni Stok Miktarı
- [ ] Depo bazlı import (multi-warehouse)
- [ ] Stok hareketine log kaydı (kim ne zaman güncelledi)
- [ ] Dry-run validation

---

### C. Müşteri Import

- [ ] E-posta ile müşteri import:
  - email, ad, soyad, telefon, müşteri grubu
- [ ] KVKK uyumu: import edilen müşteriler için rıza durumu işaretleme
- [ ] Duplicate detection: Aynı email varsa: Atla / Güncelle

---

### D. Export Sistemi

- [ ] **Ürün Export:**
  - CSV / Excel / JSON formatları
  - Filter uygulanabilir: kategori, marka, stok durumu
  - SEO alanları dahil
- [ ] **Sipariş Export:**
  - Tarih aralığı, durum, ödeme yöntemi filtresi
  - Muhasebe için: Excel formatı (tutar, KDV, fatura no, müşteri)
- [ ] **Müşteri Export:**
  - KVKK uyumu: sadece yetkili admin (F3 RBAC)
  - Müşterinin kendi verilerini indirme hakkı (KVKK gereği)
- [ ] **Stok Export:**
  - Tüm ürünler + depolar + mevcut stok
- [ ] **Merchant Feed Export** (F17'den bağımsız yedek):
  - Google Shopping CSV formatı

---

### E. Raporlama Sistemi

> ⚠️ Tüm raporlar production tabloları üzerinde AĞIR sorgu çalıştırmaz.  
> Cache + materialized view + queue-based hesaplama kullanılır.

#### E1. Satış Raporları
- [ ] Günlük / haftalık / aylık satış özeti
- [ ] Kategori bazlı satış
- [ ] Ürün bazlı satış (en çok satan ürünler)
- [ ] Marka bazlı satış
- [ ] Ödeme yöntemi bazlı satış
- [ ] Coğrafi satış dağılımı (şehir bazlı)

#### E2. Sipariş Raporları
- [ ] Sipariş durumu dağılımı
- [ ] Ortalama sepet değeri
- [ ] İptal oranı ve sebepleri
- [ ] İade oranı ve sebepleri

#### E3. Stok Raporları
- [ ] Düşük stok listesi (eşiğin altındaki ürünler)
- [ ] Hiç satılmayan ürünler (dead stock)
- [ ] Stok dönüş hızı
- [ ] Depo bazlı stok durumu

#### E4. Müşteri Raporları
- [ ] Yeni kayıt / aktif / pasif müşteri trendi
- [ ] Müşteri yaşam boyu değeri (LTV)
- [ ] Tekrar alışveriş oranı
- [ ] Müşteri segment dağılımı

#### E5. Finansal Raporlar
- [ ] Brüt hasılat
- [ ] Net hasılat (iade düşülmüş)
- [ ] KDV özeti
- [ ] İade edilen tutar

#### E6. SEO & Arama Raporları (F25/F28 ile entegre)
- [ ] En çok aranan terimler
- [ ] Sonuçsuz arama terimleri
- [ ] SEO skoruna göre ürün raporu

---

### F. Raporlama Teknik Altyapısı

- [ ] Raporlar background job olarak üretilir (büyük veri setleri için)
- [ ] Rapor dosyaları geçici storage'a kaydedilir (1 saat geçerli link)
- [ ] Cache: Aynı rapor 1 saat içinde tekrar istenirse cache'den döner
- [ ] `rapor_sorguları` tablosu: kim ne zaman hangi raporu istedi

---

### G. Admin Panel Entegrasyonu

- [ ] Import/Export menüsü admin panelde
- [ ] Dashboard widget'ları (F20 ile entegre):
  - Bugünkü satış
  - Bu ayki sipariş sayısı
  - Düşük stok uyarısı
  - Son 7 gün satış grafiği
- [ ] Rapor sayfası: tarih seçici + filtreler + "Excel İndir" butonu

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Ürün import CSV çalışıyor (validation + dry-run + hata raporu)
- [ ] Stok import çalışıyor
- [ ] Sipariş export çalışıyor (tarih + durum filtreli)
- [ ] Ürün export çalışıyor
- [ ] Satış raporu üretiliyor
- [ ] Stok raporu üretiliyor
- [ ] Raporlar queue üzerinden üretiliyor (production DB korunuyor)
- [ ] Unit ve integration testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

# F28 — SEARCH (Arama) SİSTEMİ

> **Faz:** 28 — Search  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** YÜKSEK  
> **Bağımlılık:** F5 (Product), F4 (Catalog), F6 (Inventory), F15 (Localization)  
> **Yapıdaki Yeri:** Kat 3 sonu — ürünler indexlenmeden arama yapılamaz  
> **İnşaat Analojisi:** Binadaki arama tabelaları ve yönlendirme sistemi — her şeyi bulanlar alışveriş yapar

---

## Amaç

MySQL LIKE sorgusunu aşan, Türkçe karakter/stemming destekli, filtrelenebilir, genişletilebilir arama altyapısı kurmak. Bu faz, hem F31 AI Asistan'ın hem de storefront aramasının temelidir.

---

## ⚠️ Kritik Kural

> Search abstraction zorunludur.  
> Başlangıçta MySQL Full Text kullanılabilir.  
> Ancak **tek satır core kod değiştirmeden** Meilisearch veya Elasticsearch'e geçilebilmelidir.  
> F31 (AI Asistan) bu search altyapısını kullanacaktır.

---

## Görevler

### A. Search Driver Abstraction (Plugin Mimarisi)

- [ ] `SearchDriverInterface` oluşturuldu:
  - `index(Document $doc): void`
  - `bulkIndex(array $docs): void`
  - `delete(string $id): void`
  - `search(SearchQuery $query): SearchResult`
  - `suggest(string $term): array` (autocomplete)
- [ ] Driver implementasyonları:
  - [ ] `MySQLFullTextDriver` — başlangıç
  - [ ] `MeilisearchDriver` — ileride geçiş
  - [ ] `ElasticsearchDriver` — ileride geçiş
- [ ] `.env`'den aktif driver seçimi (config değişince sıfırdan index)

---

### B. Search Index Mimarisi

- [ ] Index edilecek alanlar (ürün):
  - `ad` (product name, tüm diller)
  - `aciklama` (description, tüm diller)
  - `kisa_aciklama`
  - `sku`
  - `barkod`
  - `marka_adi`
  - `kategori_adi` (tüm parent kategoriler dahil)
  - `ozellik_degerleri` (renk: siyah, beden: M, ...)
  - `etiketler` (tags)
  - `fiyat`
  - `stok_durumu` (in_stock / out_of_stock)
  - `aktif_mi`
  - `slug` (dil bazlı)
  - `gorsel_url`
- [ ] Dil bazlı index: `urunler_tr`, `urunler_en`
- [ ] Index güncelleme trigger'ları (event bazlı):
  - `ProductUpdated` → index güncelle
  - `VariantPriceChanged` → fiyat güncelle
  - `InventoryCommitted` → stok durumu güncelle
  - `ProductPublished` → index'e ekle
  - `ProductArchived` → index'ten çıkar
- [ ] Bulk reindex komutu: `php artisan search:reindex`

---

### C. Türkçe Dil Desteği

- [ ] Türkçe karakter normalizasyonu: `ş→s, ğ→g, ı→i` (arama teriminde)
- [ ] Türkçe stemming/morphology:
  - `ayakkabı` → `ayakkabi`, `ayakkabiyi`, `ayakkabilar` eşleşir
  - `koşu` araması → `koşu ayakkabısı` bulur
- [ ] Türkçe stop words listesi (ve, ile, bir, bu... — bunlar filtreden geçirilir)
- [ ] Case-insensitive arama
- [ ] Accent-insensitive arama

---

### D. Faceted Search (Filtreli Arama)

- [ ] Filter tipleri:
  - Fiyat aralığı (range)
  - Kategori (multi-select)
  - Marka (multi-select)
  - Attribute değerleri (renk, beden, kapasite, ...) (multi-select)
  - Stok durumu (checkbox: sadece stoktakileri göster)
  - Değerlendirme puanı (min rating)
- [ ] Aggregation/facets: "Bu filtre uygulanınca kaç sonuç kalır?"
- [ ] URL'de filter state: `/ayakkabi?renk=siyah&beden=42&min_fiyat=500`
- [ ] SEO: Filtre kombinasyonları için canonical yönetimi (F16/F25 ile entegre)

---

### E. Sıralama (Sorting)

- [ ] Sıralama seçenekleri:
  - Alakalılık (relevance score)
  - En çok satan
  - En yeni eklenen
  - Fiyat: düşükten yükseğe
  - Fiyat: yüksekten düşüğe
  - Değerlendirme puanı

---

### F. Autocomplete / Type-Ahead

- [ ] Kullanıcı yazmaya başlarken öneri:
  - Ürün adı önerileri (max 5)
  - Kategori önerileri (max 3)
  - Marka önerileri (max 2)
- [ ] Öneri isteği debounced (300ms)
- [ ] `GET /api/v1/store/ara/oneri?q=adiyas` endpoint
- [ ] Autocomplete için ayrı lightweight index

---

### G. Search Analytics (Arama Analitiği)

- [ ] Her arama terimi + sonuç sayısı kaydediliyor (`arama_kayitlari` tablosu)
- [ ] Sonuçsuz aramalar ayrı işaretleniyor
- [ ] Admin panel raporu:
  - En çok aranan terimler
  - Sonuçsuz aramalar (hangi ürün eksik?)
  - Arama → tıklama → satın alma dönüşümü
- [ ] Bu veri → ürün catalog geliştirme için ipucu

---

### H. Storefront API

- [ ] `GET /api/v1/store/ara` — Arama endpoint:
  ```
  ?q=siyah+spor+ayakkabi
  &kategori=ayakkabi
  &min_fiyat=500&max_fiyat=2000
  &renk=siyah&beden=42
  &sirala=fiyat_asc
  &sayfa=1&limit=24
  ```
- [ ] Response: products + facets + pagination + total_count + query_time_ms
- [ ] `GET /api/v1/store/ara/oneri` — Autocomplete

---

### I. Admin API

- [ ] `POST /api/v1/admin/arama/reindex` — Tüm ürünleri yeniden indexle
- [ ] `GET /api/v1/admin/arama/istatistik` — Search analytics
- [ ] `GET /api/v1/admin/arama/sonucsuz` — Sonuçsuz arama listesi
- [ ] `GET /api/v1/admin/arama/saglik` — Index sağlık durumu

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Ürün araması çalışıyor
- [ ] Türkçe karakter normalizasyonu çalışıyor
- [ ] Faceted filtering çalışıyor
- [ ] Autocomplete çalışıyor
- [ ] Search driver abstraction çalışıyor (kolayca değiştirilebilir)
- [ ] Search analytics kaydediliyor
- [ ] Event bazlı index güncelleme çalışıyor
- [ ] Unit ve integration testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

# ADR 002: Database ID Stratejisi

## Status
Kabul Edildi

## Context
E-ticaret sisteminde kayıtların (sipariş, müşteri, ürün, sepet vb.) tanımlayıcıları (Primary Key), veri tabanı performansı, API güvenliği ve mikroservis uyumluluğu açısından kritik bir karardır.

Geleneksel olarak `AUTO_INCREMENT BIGINT` kullanılır, ancak bunun bazı dezavantajları vardır:
1. **Güvenlik / Tahmin Edilebilirlik**: `GET /api/orders/5` endpoint'i, sipariş sayısını açığa çıkarır (Business intelligence sızıntısı) ve ID'ler tahmin edilebilir (IDOR zafiyeti riski).
2. **Dağıtık Sistemler (Distributed Systems)**: Gelecekte sistem yatay olarak ölçeklenirse (veya birden fazla database shard olursa), ardışık sayılar çakışmalara yol açar.

Alternatif olarak `UUID v4` kullanılabilir:
- **Dezavantajı**: Tamamen rastgele olduğu için (random), B-Tree tabanlı veritabanı indekslerinde (InnoDB) "Index Fragmentation"a sebep olur. Kayıt eklendikçe yazma performansı (INSERT) ciddi oranda düşer.

## Decision
**ULID (Universally Unique Lexicographically Sortable Identifier)** veya **UUID v7** kullanılmasına karar verilmiştir. 
*Not: Laravel yerleşik olarak her ikisini de (Str::ulid() veya Str::uuid() ile uuid7) destekler.*

Sistemdeki tablolar için standart ID stratejisi şu şekilde olacaktır:
1. Tüm harici (API üzerinden) erişilen entity'lerin (Sipariş, Sepet, Müşteri, Kategori, Ürün vb.) Primary Key'leri **ULID / UUID v7** olacaktır.
2. Sadece ara bağlantı (Pivot) tabloları ve sadece sistemin içsel (internal) olarak kullandığı, dışarıya asla çıkmayan yüksek hacimli mapping log tablolarında `BIGINT (AUTO_INCREMENT)` kullanılabilir.

### Karar Gerekçeleri:
1. **Güvenlik**: ID'ler dışarıdan tahmin edilemez (örn: `01ARZ3NDEKTSV4RRFFQ69G5FAV`). İşletme metrikleri rakiplerden gizlenir.
2. **Performans**: ULID ve UUID v7, timestamp (zaman damgası) prefix'i içerir. Bu sayede alfabetik olarak sıralanabilirler (Lexicographically sortable). Veritabanına yazılırken sıralı insert yapıldığı için Index Fragmentation yaratmaz, `AUTO_INCREMENT` performansına çok yakın çalışır.
3. **Zaman Sıralaması**: ID'ler üzerinden otomatik olarak zamana göre (created_at gibi) sıralama yapılabilir.
4. **Dağıtık Üretim**: ID'ler uygulama katmanında üretilebilir. Veritabanına INSERT yapmadan önce objenin ID'si bilinir. Bu, kompleks ilişkileri (parent-child) aynı transaction içinde kaydetmeyi kolaylaştırır.

## Consequences
- ID'ler uzun (26 karakter ULID veya 36 karakter UUID string) olacağı için, Foreign Key indekslerinde BIGINT'e göre ufak bir boyut maliyeti (storage) olacaktır. Ancak günümüz SSD/Memory donanımları için bu kabul edilebilir bir "Trade-off"tur.
- URL'ler daha uzun görünecektir (örn: `site.com/orders/01ARZ3NDEKTSV4RRFFQ69G5FAV`), bu güvenlik avantajı için göze alınan bir durumdur. SEO'ya açık olan ürün ve kategori sayfalarında zaten `slug` (örn: `nike-air-max-90`) kullanılacaktır.

## Related
- F2 — Database Tasarımı
- F18 — API Standartları

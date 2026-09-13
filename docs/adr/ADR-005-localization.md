# ADR 005: Localization (Çoklu Dil) Mimarisi

## Status
Kabul Edildi

## Context
E-ticaret platformunda (Kategori adları, Ürün adları, Açıklamalar vb.) çoklu dil desteğine ihtiyaç vardır. Performansı düşürmeden, sorguları karmaşıklaştırmadan ve yeni dil eklendiğinde veritabanı şemasını değiştirmeden (No ALTER TABLE) bir çözüm bulunmalıdır.

Geleneksel çözümler:
1. **Column-based** (`name_tr`, `name_en`): Yeni dil eklenince tablo değişir. Anti-pattern.
2. **Row-based / EAV** (`product_translations` tablosu): Mükemmel normalize edilmiştir, ancak her sorguda JOIN gerektirir. Çok dilli arama ve sıralamalarda performans sorunları yaratır.
3. **JSON Column** (`name` kolonunda `{"tr": "Ayakkabı", "en": "Shoe"}`): Şema değiştirmez, Laravel destekler. Ancak MySQL'de JSON içinde Full-Text Search yavaştır ve indexing karmaşıktır.

## Decision
**Spatie/laravel-translatable (JSON tabanlı) + Search Index (F28)** yaklaşımı benimsenecektir.

### Karar Detayları:
1. Veritabanındaki çeviriler ayrı tablolarda (JOIN) değil, ilgili tablonun içinde **JSON** veri tipi olarak tutulacaktır. (Örn: `products.name` -> `{"tr": "Ayakkabı", "en": "Shoe"}`).
2. **Arama ve Sıralama Probleminin Çözümü:** Veritabanındaki JSON verisi *sadece Source of Truth* (Gerçek veri kaynağı) olarak kullanılacaktır. Müşterinin mağazada (Storefront) yaptığı aramalar ve sıralamalar **doğrudan F28 (Search Engine - Meilisearch/Elasticsearch)** üzerinden yapılacaktır. 
3. Search indexleri dil bazlı oluşturulacaktır (`products_tr`, `products_en`). Böylece JSON tabanlı çevirinin yarattığı veritabanı okuma performans sorunu tamamen baypas edilmiş olacaktır.

## Consequences
- Admin panelde ürün kaydederken veriler JSON olarak veritabanına yazılır.
- Veritabanı (MySQL) üzerinden doğrudan filtreleme (WHERE JSON_EXTRACT...) sadece admin panel gibi trafiğin düşük olduğu yerlerde yapılır. Storefront trafiği tamamen Search Index üzerinden akar.
- Yeni bir dil eklemek için hiçbir tablo şeması değişikliğine gerek kalmaz, sadece config'e `fr` (Fransızca) eklenir ve indexler yeniden oluşturulur.

## Related
- F2 — Database Tasarımı
- F15 — Localization
- F28 — Search Sistemi

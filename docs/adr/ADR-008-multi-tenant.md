# ADR 008: Multi-Tenant / Multi-Store Mimarisi

## Status
Kabul Edildi

## Context
E-ticaret sistemi SaaS (Software as a Service) olarak kullanılacaksa, tek bir veritabanı (veya kod bloğu) üzerinden birden fazla satıcıya/markaya mağaza (Store) hizmeti vermek gerekir (Shopify, Ticimax mantığı). 
Veya tek bir markanın farklı ülkelerde farklı stok/fiyatlara sahip alt mağazaları olabilir (Nike TR, Nike UK).

Bu veriyi birbirinden nasıl izole edeceğimiz (Data Isolation) sistemin kalbini oluşturur.

Geleneksel seçenekler:
1. **Database per Tenant**: Her müşteri için ayrı veritabanı oluşturulur. İzolasyon kusursuzdur, ancak 1000 mağaza olunca 1000 veritabanını yönetmek ve migration (şema) güncellemek imkansızlaşır.
2. **Schema per Tenant (PostgreSQL)**: Her mağaza için ayrı şema. MySQL'de karşılığı yoktur (Ayrı DB ile aynıdır).
3. **Shared Database, Shared Schema (Column-based)**: Tüm müşteriler aynı tabloda yer alır, her satırda `tenant_id` veya `store_id` kolonu bulunur.

## Decision
**Shared Database, Shared Schema (Tek Veritabanı, `store_id` kolonu)** yapısı kullanılacaktır.
Ayrıca hiyerarşi **Tenant -> Store -> Channel -> Locale** şeklinde olacaktır.

### Karar Detayları:
1. **Tek Veritabanı**: Tüm müşterilerin/mağazaların verisi aynı tabloda (örn: `orders`) tutulacaktır.
2. **Global Scope (İzolasyon)**: Laravel'in "Global Scopes" özelliği kullanılarak, sisteme bağlanan API request'inin hangi mağazaya ait olduğu (Header'daki `X-Store-ID` veya Domain/Host bazlı) Middleware ile tespit edilecek ve *her veritabanı sorgusuna otomatik olarak `WHERE store_id = X` şartı eklenecektir*.
3. **Hiyerarşi**:
   - `Tenant` (Şirket / Holding)
   - `Store` (Fiziksel veya Mantıksal Mağaza, örn: X Markası)
   - `Channel` (Satış Kanalı, örn: Web, Mobil Uygulama, Trendyol Entegrasyonu)
   - `Locale` (Dil ve Para Birimi, örn: TR/TRY)
4. **Fiyat ve Stok Ayrımı**: Fiyat ve stok bilgileri `Store` veya `Channel` bazlı tutulabilmelidir. (Web'de 100 TL olan ürün, Trendyol kanalında 120 TL olabilir).

## Consequences
- Yazılımcılar veritabanından veri çekerken (özellikle custom query yazarken) kazara `store_id` filtresini unuturlarsa veri sızıntısı (Data Leak) olur (Bir mağazanın siparişlerini diğeri görebilir). Bu riski sıfırlamak için ORM (Eloquent) dışına çıkılması kesinlikle yasaklanacaktır (Global scope'un çalışması için).
- Tablolardaki tüm Unique Index'ler (Örn: `slug`, `sku`) mutlaka `store_id` ile birleştirilecektir (Composite Unique Index: `store_id`, `sku`).

## Related
- F1 — Core Foundation
- F2 — Database Tasarımı

# ADR-0001: Database Selection

**Tarih:** 2026-09-13
**Durum:** ONAYLI
**Bağlam:** Platformun ana ilişkisel veritabanı (RDBMS) altyapısının seçimi. Sistemin veri bütünlüğü, karmaşık sorgu performansı, JSON veri tipi desteği ve Laravel ekosistemi ile uyumluluğu gözetilmiştir.

## Karar
Platformun **Tek Gerçeği (Single Source of Truth)** olarak **MySQL 8.0+** kullanılmasına kesin olarak karar verilmiştir.

PostgreSQL ve PostgreSQL'e özgü özellikler (örneğin schema-per-tenant, JSONB operatörleri) platformun mimarisinden **tamamen çıkarılmıştır**. Tüm geliştirme, CI/CD, test ve production ortamları sadece MySQL 8.0+ üzerinden yapılandırılacaktır.

## Gerekçe
1. **Laravel Uyumluluğu:** Laravel'in core ekosistemi ve first-party paketleri MySQL ile daha pürüzsüz çalışmaktadır.
2. **JSON Desteği:** MySQL 8.0+, Attribute Engine gibi dinamik EAV (Entity-Attribute-Value) alternatifleri için yeterli JSON kolon ve indeksleme (Generated Columns) yeteneklerine sahiptir.
3. **Standartlaştırma:** Farklı ortamlarda PostgreSQL ve MySQL ayrımından kaynaklı test ve senkronizasyon hatalarının (CI/CD vs.) önüne geçmek için teknoloji yığını daraltılmış ve standartlaştırılmıştır.
4. **Multi-Tenant Mimari:** Schema-per-tenant yerine `TenantScope` (Row-level / Column-based) izolasyonu tercih edilmiştir. Bu durum, MySQL'de çoklu veritabanı (Database-per-tenant) yerine daha kolay yönetilebilir ve ölçeklenebilir bir mimari sağlar.

## Sonuçlar (Consequences)
- `ARCHITECTURE_MASTER_SPEC.md` ve ilgili tüm dokümanlardan PostgreSQL referansları kaldırılmıştır.
- Tüm `docker-compose.yml` ve CI pipeline yapılandırmaları `mysql:8.0` kullanacak şekilde kilitlenmiştir.
- Gelecekteki tüm veritabanı tasarımları MySQL 8.0 kısıtlamaları (örneğin Foreign Key limitleri, full-text search) göz önüne alınarak yapılacaktır.

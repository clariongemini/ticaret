# F4 — CATALOG DOMAIN (Kategori, Marka, Koleksiyon)

> **Faz:** 4 — Catalog  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** YÜKSEK  
> **Bağımlılık:** F2, F3

---

## Amaç

Ürünlerin organize edileceği Catalog domain'ini (kategori ağacı, marka, koleksiyon) oluşturmak.

---

## Görevler

### A. Kategori Sistemi

- [ ] Kategori entity oluşturuldu (Domain Layer)
- [ ] Kategori repository interface oluşturuldu
- [ ] Kategori repository implementasyonu yapıldı
- [ ] Kategori çevirisi (translation) sistemi kuruldu (TR / EN ve genişletilebilir)
- [ ] Parent/child tree yapısı implementasyonu
- [ ] Nested set veya adjacency list tercih analizi yapıldı
- [ ] Kategori ağacı sorgulama servisi oluşturuldu
- [ ] Kategori SEO alanları: seo_title, seo_description, canonical_url, robots, slug, og_title, og_description, og_image
- [ ] Kategori slugı: dil bazlı (/tr/ayakkabi, /en/shoes)
- [ ] Kategori slug değişikliğinde otomatik 301 redirect kaydı

### B. Marka (Brand) Domain

- [ ] Marka entity oluşturuldu
- [ ] Marka çevirisi sistemi kuruldu
- [ ] Marka SEO alanları eklendi
- [ ] Marka sayfası URL yapısı tasarlandı
- [ ] Marka logosu / medya ilişkilendirmesi yapıldı

### C. Koleksiyon (Collection)

- [ ] Koleksiyon entity oluşturuldu
- [ ] Manuel ve dinamik (kural bazlı) koleksiyon desteği planlandı

### D. Admin API (Yönetim Paneli)

**Kategoriler:**
- [ ] `GET /api/v1/admin/kategoriler` — Liste (tree)
- [ ] `POST /api/v1/admin/kategoriler` — Oluştur
- [ ] `GET /api/v1/admin/kategoriler/{id}` — Detay
- [ ] `PUT /api/v1/admin/kategoriler/{id}` — Güncelle
- [ ] `DELETE /api/v1/admin/kategoriler/{id}` — Sil (soft delete)
- [ ] `POST /api/v1/admin/kategoriler/reorder` — Sıralama

**Markalar:**
- [ ] CRUD endpoint'leri oluşturuldu

**Koleksiyonlar:**
- [ ] CRUD endpoint'leri oluşturuldu

### E. Storefront API (Vitrin)

- [ ] `GET /api/v1/store/kategoriler` — Kategori ağacı (public)
- [ ] `GET /api/v1/store/kategoriler/{slug}` — Kategori detayı
- [ ] `GET /api/v1/store/markalar` — Marka listesi
- [ ] `GET /api/v1/store/markalar/{slug}` — Marka detayı

### F. Cache Stratejisi

- [ ] Kategori ağacı cache'lendi (Read-heavy — agresif cache)
- [ ] Cache invalidation: kategori güncellendiğinde tetikleniyor
- [ ] Marka listesi cache'lendi

### G. Event'ler

- [ ] `CategoryCreated` event oluşturuldu
- [ ] `CategoryUpdated` event oluşturuldu
- [ ] `BrandCreated` event oluşturuldu
- [ ] `BrandUpdated` event oluşturuldu

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Kategori CRUD çalışıyor
- [ ] Çok dilli kategori çalışıyor (TR/EN)
- [ ] Marka CRUD çalışıyor
- [ ] Slug sistemi çalışıyor
- [ ] SEO alanları doldurulabiliyor
- [ ] Cache çalışıyor
- [ ] Unit ve feature testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

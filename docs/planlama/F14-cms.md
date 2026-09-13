# F14 — CMS (İçerik Yönetim Sistemi)

> **Faz:** 14 — CMS  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** ORTA  
> **Bağımlılık:** F1 (Foundation), F2 (Database)

---

## Amaç

E-ticaret sistemini tamamlayan içerik yönetim sistemini kurmak. CMS ile Commerce domain birbirine gereksiz şekilde bağlanmayacaktır.

---

## Görevler

### A. Page (Sayfa) Sistemi

- [ ] `Page` entity oluşturuldu
- [ ] Sayfa çevirisi sistemi kuruldu
- [ ] Sayfa türleri: landing, legal, about, contact, custom
- [ ] Sayfa SEO alanları
- [ ] Sayfa durumu: draft / published / scheduled
- [ ] Sayfa içerik yapısı (blocks/sections veya rich text)
- [ ] Legal sayfalar: Gizlilik, KVKK, Kullanım Şartları, Mesafeli Satış Sözleşmesi, İade Politikası

### B. Blog Sistemi

- [ ] `Blog` entity (multi-blog desteği)
- [ ] `BlogPost` entity oluşturuldu
- [ ] Blog yazısı çevirisi sistemi
- [ ] Blog kategorisi
- [ ] Blog yazarı
- [ ] Blog SEO alanları
- [ ] Blog yazısı durumu: draft / published / scheduled
- [ ] Blog etiket sistemi

### C. Menu Sistemi

- [ ] `Menu` entity oluşturuldu
- [ ] `MenuItem` entity oluşturuldu (nested)
- [ ] Menu türleri: header / footer / mobile / sidebar
- [ ] Menu öğesi tipleri: kategori / ürün / sayfa / URL / koleksiyon

### D. Banner / Slider Sistemi

- [ ] `Banner` entity oluşturuldu
- [ ] Banner placement (ana sayfa hero, kategori üstü, popup vb.)
- [ ] Banner tarih aralığı (start_date / end_date)
- [ ] Banner kanal/store bazlı

### E. FAQ Sistemi

- [ ] `FAQ` entity oluşturuldu
- [ ] FAQ grup/kategori desteği
- [ ] FAQ çevirisi

### F. Media Library (F34 ile entegre)

- [ ] CMS içerikleri medya kütüphanesini kullanıyor
- [ ] Görsel, video, dosya yönetimi

### G. Admin API

**Sayfalar:**
- [ ] `GET /api/v1/admin/sayfalar`
- [ ] `POST /api/v1/admin/sayfalar`
- [ ] `PUT /api/v1/admin/sayfalar/{id}`
- [ ] `DELETE /api/v1/admin/sayfalar/{id}`

**Blog:**
- [ ] Blog CRUD endpoint'leri
- [ ] Blog yazısı CRUD endpoint'leri

**Menu:**
- [ ] Menu CRUD endpoint'leri
- [ ] MenuItem yönetimi (drag & drop sıralama)

**Banner:**
- [ ] Banner CRUD endpoint'leri

### H. Storefront API

- [ ] `GET /api/v1/store/sayfalar/{slug}` — Sayfa detayı
- [ ] `GET /api/v1/store/blog/yazilar` — Blog yazı listesi
- [ ] `GET /api/v1/store/blog/yazilar/{slug}` — Blog yazısı detayı
- [ ] `GET /api/v1/store/menüler/{location}` — Menu
- [ ] `GET /api/v1/store/bannerlar/{placement}` — Banner listesi

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Sayfa CRUD çalışıyor
- [ ] Blog CRUD çalışıyor
- [ ] Menu yönetimi çalışıyor
- [ ] Banner yönetimi çalışıyor
- [ ] Çok dilli CMS içeriği çalışıyor
- [ ] SEO alanları doldurulabiliyor
- [ ] Unit ve feature testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

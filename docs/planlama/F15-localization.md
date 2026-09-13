# F15 — LOCALIZATION (Çoklu Dil) ARCHITECTURE

> **Faz:** 15 — Localization  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** YÜKSEK  
> **⚠️ YENİ SIRALAMA:** F4 (Catalog) ve F5 (Product)'tan **ÖNCE** gelir  
> **Bağımlılık:** F2 (Database — çeviri tablo şemaları F2'de tasarlandı), F1 (Foundation)  
> **İnşaat Analojisi:** Kat planları çizilmeden dil mimarisi belirlenmeli — yoksa her odayı sonradan genişletmek duvarları yıkmak demektir

---

## ⚠️ Neden Bu Sırada?

> **Eski planda F15, F14 (CMS) sonrasına yerleştirilmişti — bu YANLIŞTIR.**  
>  
> Ürün ve kategori domain'leri oluşturulurken çeviri tabloları hazır olmalıdır.  
> Aksi halde:  
> - `urunler` tablosu Türkçe tek dilde oluşturulur  
> - Sonradan `urun_ceviri` tablosu eklenir  
> - Mevcut veriler migrate edilmek zorunda kalınır  
> - Bu, production'da ALTER TABLE krizi demektir  
>  
> **Doğru sıra:** F2 (DB şema) → F15 (Localization altyapısı) → F4 (Catalog) → F5 (Product)

---

## Amaç

İlk günden Türkçe + İngilizce desteklemek, gelecekte yeni diller eklenebilecek şekilde architecture kurmak.

---

## ⚠️ Kritik Kurallar

> - Dil metinlerini tek kolon içine JSON doldurmak yasaktır  
> - Normalized translation architecture veya gerekçeli JSON yaklaşımı (ADR-005)  
> - Language ≠ Country  
> - URL dil bazlı olmalıdır: `/tr/urunler/`, `/en/products/`

---

## Görevler

### A. Translation Architecture (ADR-005)

- [ ] Normalized translation tables:
  - `urun_ceviri`, `kategori_ceviri`, `marka_ceviri`, `sayfa_ceviri` vb.
  - Avantaj: tip güvenliği, index'lenebilir, query kolaylığı
- [ ] Her translatable entity için `_ceviri` tablosu oluşturuldu
- [ ] Varsayılan dil geri dönüş (fallback) stratejisi belirlendi

### B. Desteklenen Diller

- [ ] `diller` tablosu dolduruldu:
  - `tr` (Türkçe) — birincil dil
  - `en` (English)
- [ ] Yeni dil ekleme dokümante edildi (core kod değişmeden)

### C. URL Mimarisi

- [ ] Dil prefix'li URL yapısı: `/tr/`, `/en/`
- [ ] Slug dil bazlı: `/tr/urunler/kirmizi-ayakkabi` / `/en/products/red-shoes`
- [ ] Dil prefix routing middleware implementasyonu
- [ ] Slug değişikliğinde 301 redirect kaydı (her dil için ayrı)

### D. Hreflang Implementasyonu

- [ ] Her indexable sayfa için hreflang tag'leri üretiliyor
- [ ] `<link rel="alternate" hreflang="tr" href="...">` formatı
- [ ] `x-default` hreflang stratejisi belirlendi
- [ ] Dil değişiminde URL değişiyor (browser dili yorumu değil)
- [ ] Google'ın cloaking kurallarına uyuluyor

### E. Admin Panel Çeviri Arayüzü

- [ ] Admin panel'de dil sekmesi: ürün, kategori, sayfa çevirisi
- [ ] Eksik çeviri uyarısı
- [ ] Çeviri durumu: draft / translated / reviewed / published
- [ ] Bir dilde yayında olan ürünün diğer dilde eksik olması fallback/noindex stratejisi

### F. SEO Translation (F16 ile entegre)

- [ ] Her dil için bağımsız SEO alanları:
  - seo_title, seo_description, slug, meta, OG tags, schema textual fields
- [ ] Dil bazlı SEO yönetimi admin panel'den

### G. Para Birimi + Dil İlişkisi

- [ ] Dil ve para birimi ayrı yönetiliyor (tr → TRY, en → USD/EUR seçilebilir)
- [ ] Channel bazlı para birimi + dil eşleştirmesi

### H. Çeviri API'leri

- [ ] `GET /api/v1/admin/diller` — Dil listesi
- [ ] `POST /api/v1/admin/diller` — Yeni dil ekle
- [ ] `GET /api/v1/store/diller` — Desteklenen diller (storefront)
- [ ] `GET /api/v1/store/ceviri/{entity}/{id}` — Entity çevirisi

---

## Çıkış Kriteri (Exit Criteria)

- [ ] TR ve EN ürün çevirisi çalışıyor
- [ ] Dil bazlı URL yapısı çalışıyor
- [ ] Hreflang tag'leri doğru üretiliyor
- [ ] Slug dil bazlı ve redirect çalışıyor
- [ ] Admin panel'de çeviri yönetimi çalışıyor
- [ ] Yeni dil ekleme test edildi
- [ ] Unit ve integration testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

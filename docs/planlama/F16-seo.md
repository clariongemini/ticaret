# F16 — SEO ARCHITECTURE

> **Faz:** 16 — SEO  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** YÜKSEK  
> **Bağımlılık:** F4 (Catalog), F5 (Product), F14 (CMS), F15 (Localization)

---

## Amaç

SEO'yu "meta title alanı ekleme" olmaktan çıkarıp merkezi bir SEO domain olarak yapılandırmak. Schema.org, canonical, sitemap, robots ve merchant-ready yapı bu fazda kurulacaktır.

---

## ⚠️ Kritik Kurallar

> - JSON-LD structured data ile görünür sayfa içeriği **TUTARLI** olmalı  
> - Schema spam yapılmaz (olmayan bilgi schema'ya koyulmaz)  
> - `Disallow: /` gibi catastrophic robots.txt hatasına karşı koruma  
> - Canonical, hreflang ve robots birlikte yönetilmeli

---

## Görevler

### A. Central SEO Domain

- [ ] `SEOMeta` entity oluşturuldu (polymorphic — Product, Category, Brand, Page, Blog için)
- [ ] SEO alanları:
  - [ ] `seo_title`
  - [ ] `seo_description`
  - [ ] `canonical_url`
  - [ ] `robots` (index/noindex, follow/nofollow)
  - [ ] `og_title`
  - [ ] `og_description`
  - [ ] `og_image`
  - [ ] `slug` (dil bazlı)
- [ ] SEO inheritance + override mekanizması:
  - Global default → Category override → Product override
- [ ] Admin panel'den SEO yönetimi (her entity için)

### B. Structured Data (Schema.org) — JSON-LD

- [ ] `Organization` schema — site geneli
- [ ] `WebSite` schema + SearchAction
- [ ] `WebPage` schema — sayfa bazlı
- [ ] `BreadcrumbList` schema — her sayfada
- [ ] `Product` + `Offer` schema — ürün sayfaları
  - name, image, description, sku, brand
  - price, priceCurrency, availability
  - url, itemCondition
  - shippingDetails
  - hasMerchantReturnPolicy
- [ ] `AggregateRating` + `Review` schema
- [ ] `Article` schema — blog yazıları
- [ ] `FAQPage` schema — SSS sayfaları
- [ ] `ItemList` schema — kategori/listing sayfaları
- [ ] Schema üretimi merkezi SchemaBuilder servisi üzerinden
- [ ] Görünür içerik ile schema tutarlılığı

### C. Slug Sistemi

- [ ] Slug otomatik üretimi (Türkçe karakter dönüşümü)
- [ ] Slug benzersizlik kontrolü
- [ ] Slug değişikliğinde otomatik 301 redirect kaydı
- [ ] Slug geçmişi `slug_gecmisi` tablosuna kaydediliyor

### D. Redirect Yönetimi

- [ ] `yonlendirmeler` tablosu
- [ ] 301 / 302 redirect türleri
- [ ] Admin panel'den redirect yönetimi
- [ ] Döngüsel redirect koruması
- [ ] Redirect zinciri limitasyonu

### E. Sitemap

- [ ] Multilingual sitemap architecture:
  - `sitemap-index.xml` (ana index)
  - `sitemap-urunler-tr.xml`
  - `sitemap-urunler-en.xml`
  - `sitemap-kategoriler.xml`
  - `sitemap-sayfalar.xml`
  - `sitemap-blog.xml`
- [ ] Sitemap yalnızca index'lenmesi gereken URL'leri içeriyor
- [ ] Sitemap otomatik güncelleme (queue üzerinden, içerik değişince)
- [ ] Sitemap priority ve changefreq değerleri

### F. Robots.txt

- [ ] Robots.txt dinamik üretiliyor
- [ ] Admin panel'den yönetim
- [ ] `Disallow: /` gibi catastrophic hataya karşı:
  - Validation uyarısı
  - Onay gerektiren "tehlikeli kural" koruması

### G. Canonical URL Stratejisi

- [ ] Tüm sayfalarda canonical tag mevcut
- [ ] Filtreli URL'lerde canonical: `/ayakkabi?color=black` → canonical `/ayakkabi`
- [ ] Sayfalandırmada canonical stratejisi

### H. Faceted Navigation SEO

- [ ] Admin panel'den filtre kombinasyonları için:
  - [ ] Index / Noindex
  - [ ] Canonical
  - [ ] Crawlable / Non-crawlable
- [ ] Duplicate content tuzağından kaçınma stratejisi

### I. SEO Quality Score

- [ ] Her ürün/kategori/sayfa için SEO checklist:
  - [ ] title mevcut ve optimal uzunlukta
  - [ ] description mevcut
  - [ ] canonical doğru
  - [ ] slug uygun
  - [ ] schema mevcut
  - [ ] image alt text mevcut
  - [ ] content yeterli
  - [ ] indexable
- [ ] Admin panel'den SEO skoru görüntüleme

### J. SEO Health Check

- [ ] Duplicate title tespiti
- [ ] Missing description tespiti
- [ ] Duplicate canonical tespiti
- [ ] Missing schema tespiti
- [ ] Admin panel'den SEO sorunları listesi

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Her ürün/kategori/sayfa için SEO meta alanları yönetiliyor
- [ ] JSON-LD schema doğru üretiliyor
- [ ] Sitemap üretiliyor ve doğru URL'leri içeriyor
- [ ] Robots.txt koruması var
- [ ] Canonical URL'ler doğru
- [ ] Hreflang F15 ile entegre çalışıyor
- [ ] SEO quality score çalışıyor
- [ ] Integration testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

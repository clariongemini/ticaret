# F25 — SEO OTOMASYONu & SEO INTELLIGENCE

> **Faz:** 25 — SEO Automation  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** YÜKSEK  
> **Bağımlılık:** F16 (SEO Architecture), F5 (Product), F4 (Catalog)  
> **İnşaat Analojisi:** Akıllı bina yönetim sistemi — sensörler kendi durumunu izler, eksikleri raporlar, otomatik düzeltir

---

## Amaç

Müşteri SEO bilgisi olmasa bile sistem SEO alanlarını otomatik üretir, eksikleri tespit eder, gerçek zamanlı öneri sunar ve toplu denetimle tüm içerik SEO kalitesini garantiler.

---

## ⚠️ Temel Felsefe

> **Pasif kontrol değil — aktif otomasyon.**  
> "SEO title boş" uyarısı vermek yetmez.  
> Sistem boş bırakıldığında **akıllı default üretmelidir.**  
> Override edilebilir ama **hiçbir alan boş kalamaz.**

---

## Görevler

### A. SEO Otomatik Üretim Motoru

- [ ] `SeoAutoGeneratorService` oluşturuldu
- [ ] **Title otomatik üretimi:**
  - Ürün kaydedilirken SEO title boşsa → `{UrunAdi} | {MarkaAdi}` formatı
  - Kategori için → `{KategoriAdi} - {MağazaAdı}`
  - Blog yazısı için → yazı başlığının kendisi (trim + limit)
  - Admin tarafından override edilebilir
- [ ] **Meta description otomatik üretimi:**
  - Ürün açıklamasının ilk 155 karakteri (cümle ortasında kesilmiyor)
  - Açıklama yoksa → `{UrunAdi} ürününü {MağazaAdı}'nde incele. {Kategori} kategorisinde en iyi fiyat.`
- [ ] **Alt text otomatik üretimi:**
  - Görsel yüklenirken alt text boşsa → `{UrunAdi} {RenkAttribute} {BedenAttribute}` formatı
  - Admin override edilebilir
- [ ] **Open Graph title/description:** SEO title/description'dan otomatik miras alır (override edilebilir)
- [ ] Tüm otomatik üretim mantığı config'den değiştirilebilir (template bazlı)

---

### B. Gerçek Zamanlı SEO Öneri Motoru

- [ ] Ürün/kategori/sayfa kaydedilirken SEO öneri sonuçları döndürülüyor
- [ ] **Öneri kuralları:**
  - [ ] title < 30 karakter → "Çok kısa (ideal: 50-60 karakter)"
  - [ ] title > 65 karakter → "Çok uzun, Google'da kesilebilir"
  - [ ] description < 70 karakter → "Çok kısa (ideal: 120-160 karakter)"
  - [ ] description > 165 karakter → "Çok uzun, kesilebilir"
  - [ ] description eksik → "Eksik — otomatik oluşturulsun mu?" [Evet / Hayır butonu]
  - [ ] alt text eksik (en az 1 görselde) → "X görsel alt text eksik"
  - [ ] GTIN eksik → "Merchant Center'da onay riski"
  - [ ] Açıklama < 100 kelime → "İçerik zayıf, SEO için en az 100 kelime"
  - [ ] Açıklama başka üründe duplicate → "Duplicate içerik uyarısı"
  - [ ] Title'da ürün adı yok → "Title ürün adını içermeli"
  - [ ] Canonical yanlış → "Canonical hata tespit edildi"
  - [ ] noindex seçili ama sitemap'te → "Tutarsızlık: noindex + sitemap"
- [ ] Öneri ciddiyeti: 🔴 Kritik / 🟡 Önemli / 🔵 Bilgi

---

### C. SERP Önizleme (Google Snippet Simülasyonu)

- [ ] Admin panelde ürün/sayfa SEO alanları doldurulurken canlı Google SERP önizlemesi
- [ ] Gösterim: title (mavi, 50-60 karakter), URL (yeşil), description (gri, 120-160 karakter)
- [ ] Karakter sayısı canlı gösterimi (renk: yeşil/sarı/kırmızı)
- [ ] Mobil ve masaüstü SERP görünümü ayrı önizleme
- [ ] OG tag önizlemesi (Facebook/Twitter paylaşım görünümü)

---

### D. Toplu SEO Denetimi & Otomatik Düzeltme

- [ ] Admin panelde "SEO Denetimi Başlat" butonu
- [ ] Denetim kapsamı: Tüm ürünler / Seçili kategoriler / Tüm blog yazıları / Tüm sayfalar
- [ ] Denetim çıktısı raporu:
  - Toplam içerik sayısı
  - SEO skoru 100/100 olan içerik sayısı
  - Kritik sorun listesi (kırmızı)
  - Önemli sorun listesi (sarı)
  - En kötü 10 içerik sıralaması
- [ ] "Toplu Otomatik Doldur" seçeneği:
  - Boş SEO title'ları otomatik şablonla doldurur
  - Boş description'ları otomatik doldurur
  - Onay gerektiriyor (preview gösterilir, sonra çalışır)
- [ ] Denetim geçmişi kaydı (ne zaman çalıştırıldı, kaç sorun bulundu/düzeltildi)

---

### E. SEO Skoru (0-100)

- [ ] Her içerik için toplam SEO skoru hesaplanıyor
- [ ] Skor bileşenleri:
  | Alan | Ağırlık |
  |------|---------|
  | title mevcut + optimal uzunluk | 15 |
  | description mevcut + optimal uzunluk | 15 |
  | Görsel mevcut + alt text | 10 |
  | İçerik uzunluğu yeterli | 10 |
  | GTIN/EAN mevcut | 10 |
  | Slug SEO dostu | 10 |
  | Schema.org oluşturulmuş | 10 |
  | canonical doğru | 10 |
  | iç bağlantı (internal link) var | 5 |
  | indexable (noindex yok) | 5 |
  **Toplam: 100** |
- [ ] Admin ürün listesinde SEO skoru kolonu görünüyor
- [ ] Düşük skorlu ürünler kırmızı işaretleniyor
- [ ] "SEO Skoru < 60" filtresinde toplu düzenleme mümkün

---

### F. İçerik Kalite Motoru

- [ ] Duplicate title tespiti (site genelinde)
- [ ] Duplicate description tespiti
- [ ] Duplicate ürün açıklaması tespiti (kopyala-yapıştır)
- [ ] Açıklama uzunluğu kontrolü (< 100 kelime uyarısı)
- [ ] Keyword stuffing tespiti (aynı kelimenin %5+ tekrarı)
- [ ] H1/H2/H3 hiyerarşi kontrolü (CMS sayfalarında)

---

### G. İç Bağlantı (Internal Linking) Otomasyonu

- [ ] İlgili ürün sistemi:
  - Aynı kategoriden ürünler (satışa göre sıralı)
  - Aynı markadan ürünler
  - "Bu ürünü alanlar şunları da aldı" (sipariş verisiyle)
- [ ] Blog yazısı → ürün bağlantısı önerisi:
  - Blog içinde geçen ürün adlarına otomatik link önerisi
  - Admin onaylayıp ekleyebiliyor
- [ ] Category → sub-category link'leri otomatik
- [ ] Breadcrumb her sayfada otomatik

---

### H. Görsel SEO Otomasyonu

- [ ] Görsel upload esnasında:
  - Alt text alanı zorunlu değil ama boş bırakılırsa **uyarı + otomatik öneri**
  - Dosya adı otomatik SEO dostu yapılıyor: `nike-air-max-siyah.jpg`
  - WebP/AVIF otomatik dönüşümü (queue üzerinden)
- [ ] LCP görseli için `loading="eager"` + `fetchpriority="high"` attribute'ı API'den bildiriliyor
- [ ] Görsel boyutu yeterli değilse uyarı (Merchant için min 800x800)

---

### I. Headless SEO Veri API'si

- [ ] `GET /api/v1/store/seo/{type}/{slug}` endpoint oluşturuldu
  - Döndürdüğü veri (tek seferde, frontend için):
    ```json
    {
      "title": "...",
      "description": "...",
      "canonical": "...",
      "robots": "index,follow",
      "hreflang": [{"lang": "tr", "url": "..."}, ...],
      "og": {"title": "...", "description": "...", "image": "..."},
      "schema_ld": {...}
    }
    ```
- [ ] Frontend bu endpoint'i `<head>` oluşturmak için kullanıyor
- [ ] SSR framework (Next.js/Nuxt.js) için SSR zorunlu — SPA/CSR SEO için kabul edilmez (**ADR olarak belgelendi**)

---

### J. Google Search Console Entegrasyonu

- [ ] Google Search Console API bağlantısı (OAuth2)
- [ ] Admin panelde GSC verisi:
  - Index durumu (indexlendi mi / reddedildi mi)
  - Kapsam hataları (404, redirect sorunları)
  - Core Web Vitals puanları
  - En çok tıklanan sayfalar ve arama sorguları
- [ ] Admin panelde GSC uyarısı → ilgili ürün/sayfa linki
- [ ] **GSC entegrasyonu opsiyonel** (GSC API key olmadan sistem çalışır, entegrasyon bonus özellik)

---

### K. Merchant Center SEO Uyumu Kontrolleri

- [ ] Her ürün kaydedilirken Merchant Center uyum kontrolü:
  - title ≥ 25 karakter
  - description ≥ 150 karakter
  - Görsel minimum 800×800 px
  - Fiyat tanımlı
  - GTIN/MPN tanımlı (yoksa `identifier_exists: false` açıklaması)
  - Kargo bilgisi schema'da
  - İade politikası tanımlı
- [ ] Merchant uyum skoru: ürün başına 0-100
- [ ] Admin panelde "Merchant Hazır" / "Merchant Sorunlu" filtresi

---

### L. Yerel SEO (Local SEO) Altyapısı

- [ ] `LocalBusiness` schema.org türü yapılandırması
- [ ] Mağaza adresi, telefon, çalışma saatleri (`sistem_ayarlari`'ndan)
- [ ] Çoklu şube desteği (her şube için ayrı schema)
- [ ] `PostalAddress` structured data otomatik üretimi

---

### M. Ses Araması & Featured Snippet Altyapısı

- [ ] Ürün SSS bölümü (admin girebilir) → `FAQPage` schema otomatik
- [ ] SSS bölümü yoksa product Q&A şablonları:
  - "Bu ürün ücretsiz kargo ile geliyor mu?"
  - "İade süresi nedir?"
- [ ] Tanım paragrafı formatı desteği (featured snippet için)

---

### N. SEO A/B Test Altyapısı (Extension Point)

- [ ] Title/description varyantları kaydedilebilir
- [ ] Hangi varyantın daha iyi performans gösterdiği GSC verisiyle takip edilebilir (ileride)
- [ ] Gelecek entegrasyon için interface tanımlandı

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Boş SEO title/description **hiçbir ürün için imkansız** (otomatik doldurma çalışıyor)
- [ ] SERP önizlemesi admin panelde çalışıyor
- [ ] SEO skoru her ürün/kategori/sayfa için hesaplanıyor
- [ ] Toplu SEO denetimi çalışıyor
- [ ] İç bağlantı sistemi çalışıyor
- [ ] Görsel alt text önerisi çalışıyor
- [ ] Headless SEO API çalışıyor
- [ ] Merchant uyum skoru çalışıyor
- [ ] Unit ve integration testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

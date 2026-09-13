# 🏗️ E-COMMERCE CORE PLATFORM — ANA YAPILACAKLAR LİSTESİ

> **Proje:** Modüler, API-First, Headless E-Commerce Core Platform  
> **Dil:** PHP + MySQL  
> **Mimari:** Modular Monolith → Event-Driven → Plugin Architecture  
> **Son Güncelleme:** _(her faz sonunda güncellenecek)_  
> **Proje Durumu:** 🔴 Başlanmadı  
> **Toplam Faz:** 35 Faz / 36 Execution Step (F0–F34)

---

## 📌 NASIL KULLANILIR?

Bu dosya **master kontrol listesidir.** Her fazın ayrıntılı görevleri `/docs/planlama/` klasöründeki ilgili faz dosyasında bulunur.

**Durum ikonları:**
- 🔴 **Başlanmadı** — Henüz başlanmadı
- 🟡 **Devam Ediyor** — Aktif olarak çalışılıyor
- 🟢 **Tamamlandı** — Tüm exit criteria karşılandı
- ⏸️ **Beklemede** — Bağımlılık bekleniyor
- ❌ **Engellendi** — Sorun var, çözülmesi gerekiyor

---

## 🏠 İNŞAAT HİYERARŞİSİ VE SÜRÜM PLANI (V1 / V1.5 / V2)

> **Kural:** Kapsam çok geniş olduğu için geliştirme süreci 3 büyük Sürüm (Release) halinde planlanmıştır. 
> Bir sonraki sürüme geçmek için önceki sürüm tamamen bitmeli ve teste çıkmalıdır.

---

### 🚀 SÜRÜM V1: COMMERCE CORE & OS FOUNDATION
Bu sürüm sistemin sağlam çalışması için gereken minimum iskelettir.

#### Kat 0 & 1 — Temel ve Mimari
| Sıra | Faz | Dosya | Durum | Neden V1? |
|------|-----|-------|-------|-----------|
| 1 | **F0** — Mimari Analiz & ADR | [F0-mimari-analiz.md](docs/planlama/F0-mimari-analiz.md) | 🔴 | Mimari onayı |
| 2 | **F1** — Core Foundation | [F1-foundation.md](docs/planlama/F1-foundation.md) | 🔴 | Framework, CI/CD, Event Bus |
| 3 | **F2** — Database Tasarımı | [F2-database.md](docs/planlama/F2-database.md) | 🔴 | Şema tasarımı |
| 4 | **F18** — API Standartları | [F18-api.md](docs/planlama/F18-api.md) | 🔴 | Endpoint kuralları |
| 5 | **F19** — Plugin & Event | [F19-plugin-event.md](docs/planlama/F19-plugin-event.md) | 🔴 | Olay mimarisi |

#### Kat 2 — Güvenlik ve Gözlem (Observability)
| Sıra | Faz | Dosya | Durum | Neden V1? |
|------|-----|-------|-------|-----------|
| 6 | **F3** — Auth & RBAC | [F3-authentication.md](docs/planlama/F3-authentication.md) | 🔴 | Güvenlik kalkanı |
| 7 | **F22-A** — Security Middleware | [F22-security.md](docs/planlama/F22-security.md) | 🔴 | CSRF, Rate Limiting |
| 8 | **F23** — Observability | [F23-performance.md](docs/planlama/F23-performance.md) | 🔴 | Log, Metric, Trace (Erken kurulmalı) |

#### Kat 3 — Katalog ve Ticaret Temelleri
| Sıra | Faz | Dosya | Durum | Neden V1? |
|------|-----|-------|-------|-----------|
| 9 | **F15** — Localization | [F15-localization.md](docs/planlama/F15-localization.md) | 🔴 | Çoklu dil |
| 10 | **F4** — Catalog | [F4-catalog.md](docs/planlama/F4-catalog.md) | 🔴 | Kategori/Marka |
| 11 | **F5** — Product & Variant | [F5-product-variant.md](docs/planlama/F5-product-variant.md) | 🔴 | Ürünler |
| 12 | **F6** — Inventory | [F6-inventory.md](docs/planlama/F6-inventory.md) | 🔴 | Stok (Race Condition kilitleri) |

#### Kat 4 — Çekirdek Akış (Checkout & Sipariş)
| Sıra | Faz | Dosya | Durum | Neden V1? |
|------|-----|-------|-------|-----------|
| 13 | **F7** — Cart | [F7-cart.md](docs/planlama/F7-cart.md) | 🔴 | Sepet |
| 14 | **F9** — Payment | [F9-payment.md](docs/planlama/F9-payment.md) | 🔴 | Ödeme altyapısı |
| 15 | **F8** — Checkout | [F8-checkout.md](docs/planlama/F8-checkout.md) | 🔴 | Ödeme + Sipariş oluşturma |
| 16 | **F10** — Order Domain | [F10-order.md](docs/planlama/F10-order.md) | 🔴 | Sipariş yönetimi |
| 17 | **F11** — Shipping | [F11-shipping.md](docs/planlama/F11-shipping.md) | 🔴 | Teslimat |
| 18 | **F12** — Tax & Invoice (Temel) | [F12-tax-invoice.md](docs/planlama/F12-tax-invoice.md) | 🔴 | Vergi |

#### Kat 5 — Yönetim ve API
| Sıra | Faz | Dosya | Durum | Neden V1? |
|------|-----|-------|-------|-----------|
| 19 | **F20** — Admin Panel | [F20-admin-panel.md](docs/planlama/F20-admin-panel.md) | 🔴 | Yönetim |
| 20 | **F34** — Webhooks Out | [F34-webhook-out.md](docs/planlama/F34-webhook-out.md) | 🔴 | Dışa veri (Event Driven) |
| 21 | **F21** — Core Testing | [F21-testing.md](docs/planlama/F21-testing.md) | 🔴 | V1 için tam test coverage |

---

### 📈 SÜRÜM V1.5: GROWTH & SCALE
Sistem V1 ile ayağa kalktıktan sonra pazarlama ve SEO modülleri eklenir.

| Sıra | Faz | Dosya | Durum | İçerik |
|------|-----|-------|-------|--------|
| 22 | **F28** — Search Engine | [F28-search.md](docs/planlama/F28-search.md) | 🔴 | Elasticsearch/Meilisearch |
| 23 | **F13** — Rule Engine | [F13-promotion.md](docs/planlama/F13-promotion.md) | 🔴 | Kupon ve İndirimler |
| 24 | **F14** — CMS | [F14-cms.md](docs/planlama/F14-cms.md) | 🔴 | İçerik ve Blog |
| 25 | **F16** — SEO Arch | [F16-seo.md](docs/planlama/F16-seo.md) | 🔴 | Temel SEO |
| 26 | **F25** — SEO Auto | [F25-seo-automation.md](docs/planlama/F25-seo-automation.md) | 🔴 | SEO Otomasyon |
| 27 | **F29** — Notifications | [F29-notification.md](docs/planlama/F29-notification.md) | 🔴 | İletişim (Mail, SMS) |
| 28 | **F27** — Customer Exp | [F27-customer-experience.md](docs/planlama/F27-customer-experience.md) | 🔴 | Wishlist, Yorumlar |
| 29 | **F30** — Import/Export | [F30-import-export-reporting.md](docs/planlama/F30-import-export-reporting.md) | 🔴 | Raporlama ve dışa aktarım |

---

### 🏢 SÜRÜM V2: ENTERPRISE & B2B AI
Sistemin Pazar yeri, B2B veya SaaS platformuna dönüştüğü son evre.

| Sıra | Faz | Dosya | Durum | İçerik |
|------|-----|-------|-------|--------|
| 30 | **F32** — B2B Corporate | [F32-b2b-corporate.md](docs/planlama/F32-b2b-corporate.md) | 🔴 | Açık hesap, Firma kurgusu |
| 31 | **F33** — Subscription | [F33-subscription.md](docs/planlama/F33-subscription.md) | 🔴 | Tekrarlayan ödemeler |
| 32 | **F17** — Merchant Center | [F17-merchant-center.md](docs/planlama/F17-merchant-center.md) | 🔴 | Google Feed & Satıcı Katmanı |
| 33 | **F31** — AI Commerce | [F31-ai-commerce-assistant.md](docs/planlama/F31-ai-commerce-assistant.md) | 🔴 | RAG Yapay Zeka (Tool calling) |
| 34 | **F26** — Mobile API | [F26-mobile.md](docs/planlama/F26-mobile.md) | 🔴 | Mobil spesifik optimizasyonlar |
| 35 | **F22-B** — Full Security Audit | [F22-security.md](docs/planlama/F22-security.md) | 🔴 | OWASP ve Penetrasyon |
| 36 | **F24** — Final Prod Deploy | [F24-deployment.md](docs/planlama/F24-deployment.md) | 🔴 | V2 Anahtar teslim |

---

## ⚡ KRİTİK SIRALAMA DEĞİŞİKLİKLERİ (Eski Plana Göre)

| Değişiklik | Eski Sıra | Yeni Sıra | Gerekçe |
|-----------|----------|----------|---------|
| F15 (Localization) F4'ten ÖNCE gelir | F4 → F14 → F15 | F15 → F4 → F5 | Ürün oluşturulurken çeviri tabloları hazır olmalı |
| F18 (API) TEMEL katmanda | Paralel / geç | Kat 1 (sıra 4) | İlk endpoint yazılmadan format belirlenmeli |
| F19 (Plugin/Event) TEMEL katmanda | Geç | Kat 1 (sıra 5) | Ödeme| Sıra | Faz | Ad | Durum | % | Sorumlu |
|------|-----|-----|-------|---|--------|
| 1 | [F0](docs/planlama/F0-mimari-analiz.md) | Mimari Analiz & ADR | 🔴 | 0 | - |
| 2 | [F1](docs/planlama/F1-foundation.md) | Core Foundation | 🔴 | 0 | - |
| 3 | [F2](docs/planlama/F2-database.md) | Database Tasarımı | 🔴 | 0 | - |
| 4 | [F18](docs/planlama/F18-api.md) | API Standartları | 🔴 | 0 | - |
| 5 | [F19](docs/planlama/F19-plugin-event.md) | Plugin System & Event Bus | 🔴 | 0 | - |
| 6 | [F3](docs/planlama/F3-authentication.md) | Authentication & RBAC | 🔴 | 0 | - |
| 7 | [F22-A](docs/planlama/F22-security.md) | Güvenlik Temeli (Middleware) | 🔴 | 0 | - |
| 8 | [F15](docs/planlama/F15-localization.md) | Localization (Çoklu Dil) | 🔴 | 0 | - |
| 9 | [F4](docs/planlama/F4-catalog.md) | Catalog (Kategori/Marka) | 🔴 | 0 | - |
| 10 | [F5](docs/planlama/F5-product-variant.md) | Product & Variant & Attribute | 🔴 | 0 | - |
| 11 | [F6](docs/planlama/F6-inventory.md) | Inventory (Stok) | 🔴 | 0 | - |
| 12 | [F28](docs/planlama/F28-search.md) | Search (Arama Motoru) | 🔴 | 0 | - |
| 13 | [F7](docs/planlama/F7-cart.md) | Cart (Sepet) | 🔴 | 0 | - |
| 14 | [F13](docs/planlama/F13-promotion.md) | Promotion & Coupon | 🔴 | 0 | - |
| 15 | [F9](docs/planlama/F9-payment.md) | Payment Architecture | 🔴 | 0 | - |
| 16 | [F8](docs/planlama/F8-checkout.md) | Checkout | 🔴 | 0 | - |
| 17 | [F10](docs/planlama/F10-order.md) | Order Domain | 🔴 | 0 | - |
| 18 | [F11](docs/planlama/F11-shipping.md) | Shipping | 🔴 | 0 | - |
| 19 | [F12](docs/planlama/F12-tax-invoice.md) | Tax & Invoice | 🔴 | 0 | - |
| 20 | [F29](docs/planlama/F29-notification.md) | Notification System | 🔴 | 0 | - |
| 21 | [F14](docs/planlama/F14-cms.md) | CMS | 🔴 | 0 | - |
| 22 | [F16](docs/planlama/F16-seo.md) | SEO Architecture | 🔴 | 0 | - |
| 23 | [F25](docs/planlama/F25-seo-automation.md) | SEO Automation & Intelligence | 🔴 | 0 | - |
| 24 | [F17](docs/planlama/F17-merchant-center.md) | Google Merchant Center | 🔴 | 0 | - |
| 25 | [F27](docs/planlama/F27-customer-experience.md) | Customer Experience | 🔴 | 0 | - |
| 26 | [F31](docs/planlama/F31-ai-commerce-assistant.md) | AI Commerce Assistant | 🔴 | 0 | - |
| 27 | [F20](docs/planlama/F20-admin-panel.md) | Admin Panel | 🔴 | 0 | - |
| 28 | [F26](docs/planlama/F26-mobile.md) | Mobile Integration | 🔴 | 0 | - |
| 29 | [F30](docs/planlama/F30-import-export-reporting.md) | Import/Export & Raporlama | 🔴 | 0 | - |
| 30 | [F23](docs/planlama/F23-performance.md) | Performance & Observability | 🔴 | 0 | - |
| 31 | [F21](docs/planlama/F21-testing.md) | Test (Tam Kapsam) | 🔴 | 0 | - |
| 32 | [F22-B](docs/planlama/F22-security.md) | Security Full Audit | 🔴 | 0 | - |
| 33 | [F24](docs/planlama/F24-deployment.md) | Production Deployment | 🔴 | 0 | - |ion.md) | SEO Automation & Intelligence | 🔴 | 0 | - |
| 22 | [F17](docs/planlama/F17-merchant-center.md) | Google Merchant Center | 🔴 | 0 | - |
| 23 | [F27](docs/planlama/F27-customer-experience.md) | Customer Experience | 🔴 | 0 | - |
| 24 | [F20](docs/planlama/F20-admin-panel.md) | Admin Panel | 🔴 | 0 | - |
| 25 | [F26](docs/planlama/F26-mobile.md) | Mobile Integration | 🔴 | 0 | - |
| 26 | [F23](docs/planlama/F23-performance.md) | Performance & Observability | 🔴 | 0 | - |
| 27 | [F21](docs/planlama/F21-testing.md) | Test (Tam Kapsam) | 🔴 | 0 | - |
| 28 | [F22-B](docs/planlama/F22-security.md) | Security Full Audit | 🔴 | 0 | - |
| 29 | [F24](docs/planlama/F24-deployment.md) | Production Deployment | 🔴 | 0 | - |

---

## 🔁 PARALEL YÜRÜTÜLEBİLEN FAZLAR

Bazı fazlar kendi katmanı içinde aynı anda yürütülebilir:

```
Kat 3 paralel:  F15 bitince → F4 + F5 paralel başlayabilir (F6, F5 biter bitmez)
Kat 4 paralel:  F11 + F12 paralel başlayabilir (ikisi de F10'a bağlı)
Kat 5 paralel:  F14 + F16 paralel başlayabilir (F14 CMS, F16 SEO bağımsız)
Kat 7 paralel:  F20 + F26 paralel başlayabilir (ikisi de API'nin consumer'ı)
```

---

## 🏁 ARCHITECTURAL QUALITY GATE KONTROL LİSTESİ

F0 bitmeden geçilemez. Bu 25 soru cevaplanmış olmalı:

- [ ] 1. Yeni payment provider core değiştirilmeden eklenebilir mi?
- [ ] 2. Yeni shipping provider aynı şekilde eklenebilir mi?
- [ ] 3. Yeni product attribute dinamik eklenebilir mi?
- [ ] 4. Yeni dil eklenebilir mi?
- [ ] 5. Web + mobile aynı inventory source of truth kullanıyor mu?
- [ ] 6. Race condition (stok) engelleniyor mu?
- [ ] 7. Duplicate payment engelleniyor mu?
- [ ] 8. Duplicate order engelleniyor mu?
- [ ] 9. Merchant feed DB ile fiyat/stok tutarlı mı?
- [ ] 10. JSON-LD DB ile tutarlı mı?
- [ ] 11. hreflang doğru kuruluyor mu?
- [ ] 12. Canonical doğru kuruluyor mu?
- [ ] 13. API versioning var mı?
- [ ] 14. API idempotency var mı?
- [ ] 15. RBAC var mı?
- [ ] 16. Audit log var mı?
- [ ] 17. Backup/restore planı var mı?
- [ ] 18. Disaster recovery planı var mı?
- [ ] 19. Observability (log+metric+trace) var mı?
- [ ] 20. Test stratejisi belirlendi mi?
- [ ] 21. Plugin architecture var mı?
- [ ] 22. Frontend backend'den bağımsız mı?
- [ ] 23. Headless frontend SSR zorunlu mu? (CSR/SPA SEO için kabul edilemez)
- [ ] 24. SEO otomasyonu (boş alan kalmaması) sağlandı mı?
- [ ] 25. **Yeni müşteri, core değiştirilmeden eklenebilir mi?** ← EN KRİTİK

---

## 📋 DEFINITION OF DONE

Bir modül ancak **tüm** şartlar karşılandığında DONE'dır:

- [ ] Implementation tamamlandı
- [ ] Unit testleri yazıldı ve geçiyor
- [ ] Integration testleri yazıldı ve geçiyor
- [ ] Security testleri yazıldı ve geçiyor
- [ ] Dokümantasyon güncellendi
- [ ] Migration var ve çalışıyor
- [ ] API Contract tanımlandı
- [ ] Error handling tamamlandı
- [ ] Observability eklendi (log + metric)
- [ ] Performance kabul edilebilir
- [ ] Edge case'ler ele alındı

---

## ⚠️ TEMEL KURALLAR

| Kural | Açıklama |
|-------|----------|
| 🚫 Kod önce değil | F0 Architecture Gate geçmeden hiçbir kod yazılmaz |
| 🚫 Frontend fiyatına güvenme | Tüm hesaplamalar backend'de |
| 🚫 Hardcoded KDV/döviz/dil | Bunlar configuration'dan gelir |
| 🚫 Floating point para | `DECIMAL(15,2)` veya `BIGINT` |
| 🚫 DB'de Türkçe karakter | ş→s, ğ→g, ı→i, ö→o, ü→u, ç→c |
| 🚫 Browser redirect'e güven | Webhook-first payment |
| 🚫 Kart bilgisi sakla | PCI DSS scope minimal |
| 🚫 Core'u müşteri için değiştir | Plugin/config extension point |
| 🚫 Boş SEO alanı | Sistem otomatik doldurur (F25) |
| 🚫 CSR/SPA frontend | Headless'ta SSR zorunlu (SEO) |
| ✅ Her modül test + doc + security | Bunlar olmadan DONE değil |
| ✅ Her değişiklik single source of truth | Duplicated business logic yasak |

---

## 📊 GENEL İLERLEME

```
████░░░░░░░░░░░░░░░░░░░░░░░░░░ 0% — Tüm Proje
```

| Alan | Tamamlanan | Toplam |
|------|-----------|--------|
| Kat 0 (Karar) | 0 | 1 |
| Kat 1 (Temel) | 0 | 4 |
| Kat 2 (Kolonlar) | 0 | 2 |
| Kat 3 (Ana Duvarlar) | 0 | 5 |
| Kat 4 (Ticaret Akışı) | 0 | 10 |
| Kat 5 (İçerik & SEO) | 0 | 4 |
| Kat 6 (CX & AI) | 0 | 2 |
| Kat 7 (Admin & Mobil & Veri) | 0 | 4 |
| Kat 8 (Performans) | 0 | 1 |
| Kat 9 (Denetim) | 0 | 2 |
| Kat 10 (Teslim) | 0 | 1 |
| **Toplam** | **0** | **36** |

---

## 📁 TÜM DOSYA YAPISI

```
/Volumes/SSD/Ticaret/
├── YAPILACAKLAR.md              ← Bu dosya (master kontrol)
├── yapilacaklar.md              ← Orijinal gereksinim belgesi
└── docs/
    ├── planlama/
    │   ├── F0-mimari-analiz.md        [Kat 0]
    │   ├── F1-foundation.md           [Kat 1]
    │   ├── F2-database.md             [Kat 1]
    │   ├── F18-api.md                 [Kat 1]
    │   ├── F19-plugin-event.md        [Kat 1]
    │   ├── F3-authentication.md       [Kat 2]
    │   ├── F22-security.md            [Kat 2 + Kat 9]
    │   ├── F15-localization.md        [Kat 3 - ÖNCE]
    │   ├── F4-catalog.md              [Kat 3]
    │   ├── F5-product-variant.md      [Kat 3]
    │   ├── F6-inventory.md            [Kat 3]
    │   ├── F28-search.md              [Kat 3 ⭐ YENİ]
    │   ├── F32-b2b-corporate.md       [Kat 4 ⭐ YENİ]
    │   ├── F7-cart.md                 [Kat 4]
    │   ├── F13-promotion.md           [Kat 4 (Rule Engine)]
    │   ├── F9-payment.md              [Kat 4]
    │   ├── F8-checkout.md             [Kat 4]
    │   ├── F10-order.md               [Kat 4]
    │   ├── F33-subscription.md        [Kat 4 ⭐ YENİ]
    │   ├── F11-shipping.md            [Kat 4]
    │   ├── F12-tax-invoice.md         [Kat 4]
    │   ├── F29-notification.md        [Kat 4 ⭐ YENİ]
    │   ├── F14-cms.md                 [Kat 5]
    │   ├── F16-seo.md                 [Kat 5]
    │   ├── F25-seo-automation.md      [Kat 5 ⭐ YENİ]
    │   ├── F17-merchant-center.md     [Kat 5]
    │   ├── F27-customer-experience.md [Kat 6 ⭐ YENİ]
    │   ├── F31-ai-commerce-assistant.md [Kat 6 ⭐ YENİ]
    │   ├── F20-admin-panel.md         [Kat 7]
    │   ├── F26-mobile.md              [Kat 7 ⭐ YENİ]
    │   ├── F30-import-export-reporting.md [Kat 7 ⭐ YENİ]
    │   ├── F34-webhook-out.md         [Kat 7 ⭐ YENİ]
    │   ├── F23-performance.md         [Kat 8]
    │   ├── F21-testing.md             [Kat 9]
    │   └── F24-deployment.md          [Kat 10]
    └── adr/
        ├── ADR-001-framework.md
        ├── ADR-002-database-id.md
        ├── ADR-003-payment-architecture.md
        ├── ADR-004-inventory-reservation.md
        ├── ADR-005-localization.md
        ├── ADR-006-seo-architecture.md
        ├── ADR-007-plugin-system.md
        └── ADR-008-multi-tenant.md
```

---

> _Her faz tamamlandığında:_  
> _1. Tablodaki durum güncellenir (🔴 → 🟢)_  
> _2. İlgili faz dosyasındaki checkbox'lar işaretlenir_  
> _3. "Son güncelleme" tarihi güncellenir_  
> _4. Genel ilerleme çubuğu güncellenir_
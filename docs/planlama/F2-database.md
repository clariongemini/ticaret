# F2 — DATABASE TASARIMI VE MİGRATION'LAR

> **Faz:** 2 — Database  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** KRİTİK  
> **Bağımlılık:** F0 (ADR-002 ID stratejisi), F1 (migration altyapısı)

---

## Amaç

Tüm domain'lerin veritabanı tablolarını, ilişkilerini, index'lerini ve kısıtlarını tasarlamak ve migration olarak oluşturmak.

---

## Önemli Kurallar

- Tablo ve kolon isimleri **Türkçe ASCII** (ş→s, ğ→g, ı→i, ö→o, ü→u, ç→c, İ→I)
- Global teknik standartlar İngilizce kalır: `id`, `uuid`, `created_at`, `updated_at`, `deleted_at`
- ID stratejisi ADR-002'ye göre (ULID/UUID/BIGINT)
- Foreign key kısıtları veritabanı seviyesinde tanımlanır
- Soft delete yalnızca anlamlı domain'lerde kullanılır
- Floating point para birimi **KESİNLİKLE** kullanılmaz — `DECIMAL(15,2)` veya `BIGINT` (minor units)

---

## ⚠️ KRİTİK MİMARİ UYARISI

> F2, tüm domain tablolarının şemasnı belirleyen fazır.  
> Aşağıdaki tablolar faz sıralaması gözlemlenince "ileride ekleriz" gibi görünebilir — ancak **şöyle değildir:**
>
> **Çeviri tabloları (F15):** `kategori_ceviri`, `urun_ceviri`, `marka_ceviri`, `sayfa_ceviri` vb.  
> F15 bu tabloları implement eder, ama **şema burada (F2'de) tasarlanır.**  
> Kategori oluşturulmadan önce çeviri mimarisi hazır olmalı — yoksa sonradan ALTER TABLE krizi yaşanır.
>
> **SEO tabloları (F16):** `seo_meta`, `slug_gecmisi`, `yonlendirmeler`  
> F16 bunları implement eder, **şema F2'de tasarlanır.**
>
> **Öneri/CX tabloları (F27):** `favori_listeler`, `stok_bildirimleri`, `fiyat_bildirimleri`, `degerlendirmeler`  
> F27 implement eder, **şema F2'de tasarlanır.**

---

## Görevler

### A. Çekirdek (Core) Tablolar

#### Tenant / Store
- [ ] `kiracılar` (tenants) tablosu oluşturuldu
- [ ] `magazalar` (stores) tablosu oluşturuldu
- [ ] `kanallar` (channels) tablosu oluşturuldu
- [ ] `bolge_ayarlari` (store settings) tablosu oluşturuldu

#### Localization
- [ ] `diller` (languages) tablosu oluşturuldu
- [ ] `para_birimleri` (currencies) tablosu oluşturuldu
- [ ] `ulkeler` (countries) tablosu oluşturuldu
- [ ] `doviz_kurlari` (exchange rates) tablosu oluşturuldu

### B. Catalog Domain Tabloları

- [ ] `kategoriler` tablosu oluşturuldu (parent_id, slug, tree)
- [ ] `kategori_ceviri` (category translations) tablosu oluşturuldu
- [ ] `markalar` tablosu oluşturuldu
- [ ] `marka_ceviri` tablosu oluşturuldu
- [ ] `koleksiyonlar` tablosu oluşturuldu

### C. Product Domain Tabloları

- [ ] `urun_tipleri` (product types) tablosu oluşturuldu
- [ ] `urunler` tablosu oluşturuldu
- [ ] `urun_ceviri` tablosu oluşturuldu
- [ ] `urun_varyantlari` tablosu oluşturuldu
- [ ] `urun_medyalari` tablosu oluşturuldu
- [ ] `urun_etiketleri` tablosu oluşturuldu
- [ ] `urun_kategori` (pivot) tablosu oluşturuldu

### D. Attribute Domain Tabloları

- [ ] `ozellik_gruplari` tablosu oluşturuldu
- [ ] `ozellikler` (attribute definitions) tablosu oluşturuldu
- [ ] `ozellik_secenekleri` (attribute options) tablosu oluşturuldu
- [ ] `urun_tip_ozellik` (product_type ↔ attribute pivot) tablosu oluşturuldu
- [ ] `urun_ozellik_degerleri` tablosu oluşturuldu
- [ ] `varyant_ozellik_degerleri` tablosu oluşturuldu

### E. Pricing Domain Tabloları

- [ ] `fiyat_gruplari` tablosu oluşturuldu
- [ ] `fiyatlar` tablosu oluşturuldu (base, sale, compare_at, cost, channel, currency, customer_group)
- [ ] `toplu_fiyatlar` (tier pricing) tablosu oluşturuldu
- [ ] `zamanlanmis_fiyatlar` (scheduled prices) tablosu oluşturuldu

### F. Inventory Domain Tabloları

- [ ] `depolar` (warehouses) tablosu oluşturuldu
- [ ] `stok_konumlari` (stock locations) tablosu oluşturuldu
- [ ] `stoklar` (inventory items) tablosu oluşturuldu
- [ ] `stok_hareketleri` (inventory movements) tablosu oluşturuldu
- [ ] `stok_rezervasyonlari` tablosu oluşturuldu

### G. Customer Domain Tabloları

- [ ] `musteriler` tablosu oluşturuldu
- [ ] `musteri_gruplari` tablosu oluşturuldu
- [ ] `musteri_adresleri` tablosu oluşturuldu
- [ ] `musteri_oturumlari` tablosu oluşturuldu
- [ ] `musteri_cihazlari` tablosu oluşturuldu
- [ ] `izinler` (consents/KVKK) tablosu oluşturuldu

### H. Auth / Authorization Tabloları

- [ ] `kullanicilar` (admin users) tablosu oluşturuldu
- [ ] `roller` tablosu oluşturuldu
- [ ] `yetkiler` (permissions) tablosu oluşturuldu
- [ ] `kullanici_rol` (pivot) tablosu oluşturuldu
- [ ] `rol_yetki` (pivot) tablosu oluşturuldu
- [ ] `token_listesi` (token blacklist / revocation) tablosu oluşturuldu

### I. Cart / Checkout Tabloları

- [ ] `sepetler` tablosu oluşturuldu
- [ ] `sepet_kalemleri` tablosu oluşturuldu
- [ ] `odeme_surecleri` (checkout sessions) tablosu oluşturuldu

### J. Order Domain Tabloları

- [ ] `siparisler` tablosu oluşturuldu (snapshot alanları dahil)
- [ ] `siparis_kalemleri` tablosu oluşturuldu (sku_snapshot, name_snapshot, price_snapshot, tax_snapshot)
- [ ] `siparis_adresleri` tablosu oluşturuldu (snapshot)
- [ ] `siparis_durum_gecmisi` tablosu oluşturuldu
- [ ] `siparis_notlari` tablosu oluşturuldu

### K. Payment Domain Tabloları

- [ ] `odeme_yontemleri` tablosu oluşturuldu
- [ ] `odeme_saglayicilari` tablosu oluşturuldu
- [ ] `odemeler` tablosu oluşturuldu
- [ ] `odeme_islemleri` tablosu oluşturuldu
- [ ] `iade_talepleri` (refunds) tablosu oluşturuldu
- [ ] `webhook_olaylari` tablosu oluşturuldu

### L. Shipping Domain Tabloları

- [ ] `kargo_saglayicilari` tablosu oluşturuldu
- [ ] `kargo_kurallari` tablosu oluşturuldu
- [ ] `gonderi_talepleri` (shipments) tablosu oluşturuldu
- [ ] `kargo_takip` tablosu oluşturuldu

### M. Tax / Invoice Tabloları

- [ ] `vergi_siniflari` tablosu oluşturuldu
- [ ] `vergi_oranlari` tablosu oluşturuldu
- [ ] `faturalar` tablosu oluşturuldu
- [ ] `fatura_kalemleri` tablosu oluşturuldu

### N. Promotion Domain Tabloları

- [ ] `kampanyalar` tablosu oluşturuldu
- [ ] `kampanya_kurallari` tablosu oluşturuldu
- [ ] `kuponlar` tablosu oluşturuldu
- [ ] `kupon_kullanim` tablosu oluşturuldu

### O. SEO Domain Tabloları

- [ ] `seo_meta` tablosu oluşturuldu (polymorphic)
- [ ] `slug_gecmisi` (redirect log) tablosu oluşturuldu
- [ ] `yonlendirmeler` (redirects) tablosu oluşturuldu
- [ ] `robot_kurallari` tablosu oluşturuldu

### P. Content / CMS Tabloları

- [ ] `sayfalar` tablosu oluşturuldu
- [ ] `sayfa_ceviri` tablosu oluşturuldu
- [ ] `bloglar` tablosu oluşturuldu
- [ ] `blog_yazilari` tablosu oluşturuldu
- [ ] `blog_yazi_ceviri` tablosu oluşturuldu
- [ ] `menüler` tablosu oluşturuldu
- [ ] `menu_ogesi` tablosu oluşturuldu
- [ ] `bannerlar` tablosu oluşturuldu

### Q. Media Domain Tabloları

- [ ] `medyalar` (media files) tablosu oluşturuldu
- [ ] `medya_donusumleri` (transformations) tablosu oluşturuldu

### R. Review Domain Tabloları

- [ ] `degerlendirmeler` tablosu oluşturuldu
- [ ] `degerlendirme_oylamalari` tablosu oluşturuldu

### S. Notification / Analytics Tabloları

- [ ] `bildirim_sablon` tablosu oluşturuldu
- [ ] `bildirim_log` tablosu oluşturuldu
- [ ] `analitik_olaylar` (first-party events) tablosu oluşturuldu

### T. Customer Experience Tabloları (F27'de implement edilecek, şema burada)

- [ ] `favori_listeler` tablosu oluşturuldu
- [ ] `favori_liste_kalemleri` tablosu oluşturuldu
- [ ] `son_gorutulenler` tablosu oluşturuldu
- [ ] `stok_bildirimleri` (back-in-stock) tablosu oluşturuldu
- [ ] `fiyat_bildirimleri` (price drop alert) tablosu oluşturuldu
- [ ] `cihaz_push_tokenlari` (F26 — push notification) tablosu oluşturuldu
- [ ] `aboneler` (newsletter) tablosu oluşturuldu
- [ ] `musteri_puanlari` (loyalty) tablosu oluşturuldu

### T. Audit / System Tabloları

- [ ] `denetim_kayitlari` (audit logs) tablosu oluşturuldu
- [ ] `sistem_ayarlari` tablosu oluşturuldu
- [ ] `ozellik_bayraklari` (feature flags) tablosu oluşturuldu
- [ ] `arka_plan_isleri` (jobs) tablosu oluşturuldu
- [ ] `basarisiz_isler` (failed jobs) tablosu oluşturuldu
- [ ] `idempotency_anahtarlari` tablosu oluşturuldu
- [ ] `giden_kutusu` (outbox events) tablosu oluşturuldu
- [ ] `webhook_teslimleri` tablosu oluşturuldu

### U. ERD Belgesi

- [ ] Tüm tablolar ERD'de gösterildi
- [ ] Her tablo için: isim, amaç, kolonlar, tip, nullable, index, unique, FK belgelendi
- [ ] `docs/DATABASE.md` yazıldı

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Tüm migration'lar yazıldı
- [ ] Tüm tablolarda foreign key kısıtları var
- [ ] Tüm tablolarda gerekli index'ler var
- [ ] Floating point para birimi yok (DECIMAL/BIGINT kullanılıyor)
- [ ] ERD belgesi tamamlandı
- [ ] Migration test edildi (fresh migration çalışıyor)
- [ ] Tablo isimleri Türkçe ASCII convention'a uyuyor

---

_Son güncelleme: -_  
_Sorumlu: -_

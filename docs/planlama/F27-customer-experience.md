# F27 — CUSTOMER EXPERIENCE (CX) ALTYAPISI

> **Faz:** 27 — Customer Experience  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** ORTA-YÜKSEK  
> **Bağımlılık:** F10 (Order), F5 (Product), F6 (Inventory), F13 (Promotion), F26 (Mobile — Push için)  
> **İnşaat Analojisi:** Binanın içinin döşenmesi — müşteriyi içeride tutan, geri getiren deneyimler

---

## Amaç

Müşteriyi platforma bağlayan, tekrar alışverişe yönlendiren ve deneyimi kişiselleştiren müşteri deneyimi katmanını oluşturmak.

---

## Görevler

### A. Wishlist (Favori / İstek Listesi)

- [ ] `favori_listeler` tablosu oluşturuldu
- [ ] `favori_liste_kalemleri` tablosu oluşturuldu
- [ ] Guest wishlist (token bazlı) + Customer wishlist
- [ ] Giriş yapıldığında guest → customer wishlist merge
- [ ] Birden fazla liste desteği: "Doğum Günü Listesi", "Ev Eşyaları"
- [ ] Listeyi paylaşma (public link)
- [ ] API:
  - `GET /api/v1/store/favorilerim`
  - `POST /api/v1/store/favorilerim/ekle`
  - `DELETE /api/v1/store/favorilerim/{id}`
  - `GET /api/v1/store/favorilerim/{list_id}/paylas` — Paylaşılabilir link
- [ ] Ürün listesinde favori ikonu durumu (müşteriye göre)

---

### B. Son Görüntülenenler (Recently Viewed)

- [ ] `son_gorutulenler` tablosu (musteri_id veya session bazlı)
- [ ] Ürün sayfası görüntülendiğinde otomatik kayıt
- [ ] Maksimum 20 kayıt, eskisi silinir (FIFO)
- [ ] `GET /api/v1/store/son-gorutulenler` endpoint

---

### C. Ürün Karşılaştırma (Compare Products)

- [ ] Maksimum 4 ürün karşılaştırma (session bazlı)
- [ ] Karşılaştırma tablosu: ortak attribute'lar yan yana
- [ ] `POST /api/v1/store/karsilastir/ekle`
- [ ] `GET /api/v1/store/karsilastir`
- [ ] `DELETE /api/v1/store/karsilastir/{id}`

---

### D. Stok Gelince Bildir (Back in Stock Alert)

- [ ] `stok_bildirimleri` tablosu oluşturuldu
- [ ] Stokta olmayan ürünlerde "Stok gelince bildir" butonu
- [ ] Email + Push notification seçeneği
- [ ] Stok geldiğinde (`InventoryRestocked` event) → kayıtlı kullanıcılara bildirim
- [ ] Admin panelde stok bildirimi listesi (hangi ürün, kaç kişi bekliyor)

---

### E. Fiyat Düşünce Bildir (Price Drop Alert)

- [ ] `fiyat_bildirimleri` tablosu oluşturuldu
- [ ] Ürün sayfasında "Fiyat düşünce bildir" butonu
- [ ] Müşteri hedef fiyat girebilir (opsiyonel)
- [ ] Fiyat değiştiğinde (`VariantPriceChanged` event) → kontrol
- [ ] Hedef fiyata ulaşıldığında (veya herhangi fiyat düşüşünde) → bildirim

---

### F. Ürün Öneri Motoru (Recommendation Engine)

- [ ] **Kural tabanlı öneriler (MVP — hemen çalışan):**
  - `GET /api/v1/store/urunler/{id}/benzer` — Aynı kategoriden, benzer fiyat aralığı
  - `GET /api/v1/store/urunler/{id}/aynı-marka` — Aynı markadan diğer ürünler
  - `GET /api/v1/store/urunler/{id}/tamamlayıcı` — Genellikle beraber alınan (sipariş verisinden)
  - `GET /api/v1/store/anasayfa/onerilen` — En çok satan + yeni gelen miksi
- [ ] **Sipariş bazlı öneriler (collaborative filtering):**
  - "Bu ürünü alanlar şunları da aldı" — sipariş co-occurrence analizi
  - Basit SQL sorgusuyla: aynı siparişteki diğer ürünler
- [ ] **Kişiselleştirilmiş öneriler (müşteri bazlı):**
  - Müşterinin satın alma geçmişine göre kategori tercihi
  - Son görüntülenenlere göre öneri
- [ ] Öneri motoru extension point — ileride ML tabanlı motorla değiştirilebilir

---

### G. Review & Rating Sistemi

- [ ] `degerlendirmeler` tablosu (F2'de tasarlandı, bu fazda implement edildi)
- [ ] Rating: 1-5 yıldız (tam sayı)
- [ ] Review alanları: başlık, içerik, anonim mi, doğrulanmış satın alma mı
- [ ] **Moderation workflow:**
  - Yeni yorum → `pending` durumunda
  - Admin onaylar → `published`
  - Admin reddeder → `rejected` + müşteriye bildirim
  - Auto-publish seçeneği (onay gerekmez, admin tercihi)
- [ ] **Fake review koruması:**
  - `verified_purchase`: Bu ürünü satın almış mı kontrolü
  - Aynı IP'den çok sayıda yorum uyarısı
  - Rate limiting: Bir kullanıcı günde X yorumdan fazla bırakamaz
- [ ] `AggregateRating` güncel tutulması:
  - Yeni yorum onaylandığında ürünün ortalama puanı güncelleniyor
  - Schema.org'daki rating ile DB tutarlı
- [ ] Review özet: "X değerlendirme, ort. Y/5"
- [ ] API:
  - `GET /api/v1/store/urunler/{id}/degerlendirmeler`
  - `POST /api/v1/store/urunler/{id}/degerlendirme-yap`
  - `GET /api/v1/admin/degerlendirmeler` — Onay kuyruğu
  - `POST /api/v1/admin/degerlendirmeler/{id}/onayla`
  - `POST /api/v1/admin/degerlendirmeler/{id}/reddet`

---

### H. Loyalty / Puan Sistemi (Extension Point)

- [ ] `musteri_puanlari` tablosu oluşturuldu
- [ ] Puan kazanma kuralları (sipariş tamamlandığında X puan)
- [ ] Puan harcama (checkout'ta puan ile indirim)
- [ ] Puan geçerlilik süresi
- [ ] Puan geçmişi
- [ ] Admin panelden puan yönetimi
- [ ] **Not:** İlk versiyon basit kalabilir, ileride geliştirilecek extension point bırakıldı

---

### I. Abone Ol / Newsletter

- [ ] `aboneler` tablosu oluşturuldu
- [ ] Email aboneliği (header/footer formu)
- [ ] KVKK uyumu: açık rıza alındı
- [ ] Double opt-in (onay emaili)
- [ ] Abonelik iptal linki (tek tıkla)
- [ ] Admin panelden abone listesi + export

---

### J. Müşteri Dashboard

- [ ] `GET /api/v1/store/hesabim` — Müşteri bilgileri + özet istatistikler
- [ ] Hesabım sayfası bölümleri:
  - [ ] Siparişlerim
  - [ ] Favorilerim
  - [ ] Adreslerim
  - [ ] Şifre değiştir
  - [ ] İletişim tercihleri (email, SMS, push)
  - [ ] KVKK/Veri ayarları (veri sil, indir)
  - [ ] Puan durumu (loyalty)

---

### K. Sosyal Paylaşım Altyapısı

- [ ] Her ürün sayfası için özelleştirilmiş OG tag'leri ✅ (F16'da mevcut)
- [ ] "Paylaş" butonu için native share API desteği (mobil)
- [ ] `utm_source` + `utm_medium` tracking ile paylaşım link'leri

---

### L. Customer Journey Events

- [ ] `analitik_olaylar` tablosuna CX event'leri kaydediliyor:
  - `wishlist_added`, `wishlist_removed`
  - `product_compared`
  - `review_submitted`
  - `price_alert_set`, `stock_alert_set`
  - `recommendation_clicked`
  - `newsletter_subscribed`
- [ ] Bu event'ler gelecekte segmentasyon ve remarketing için kullanılır

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Wishlist çalışıyor (guest + kayıtlı)
- [ ] Son görüntülenenler çalışıyor
- [ ] Ürün karşılaştırma çalışıyor
- [ ] Stok gelince bildir çalışıyor
- [ ] Fiyat düşünce bildir çalışıyor
- [ ] Ürün öneri sistemi (kural tabanlı) çalışıyor
- [ ] Review sistemi çalışıyor (moderation dahil)
- [ ] AggregateRating schema güncel kalıyor
- [ ] Push bildirimleri F26 ile entegre çalışıyor
- [ ] Unit ve integration testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

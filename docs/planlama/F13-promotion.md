# F13 — ADVANCED RULE ENGINE (Dinamik Kural & Kampanya Motoru)

> **Faz:** 13 — Rule Engine & Promotions  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** YÜKSEK  
> **Bağımlılık:** F7 (Cart), F5 (Product), F4 (Catalog), F32 (B2B - Müşteri Grupları)  
> **Yapıdaki Yeri:** Kat 4 — Sepet ile ödeme arasında çalışır  
> **İnşaat Analojisi:** Binanın akıllı havalandırma sistemi — şartlara göre dinamik tepki verir

---

## Amaç

E-ticaret sistemini "sabit indirim kuponları" seviyesinden çıkarıp, Enterprise sınıfı bir **Rule Engine (Kural Motoru)** seviyesine yükseltmek. `Şartlar (Conditions)` ve `Aksiyonlar (Actions)` mantığıyla çalışarak, kod değiştirmeden sınırsız kampanya kurgusu yaratabilmek.

---

## Görevler

### A. Rule Engine Mimarisi (Condition & Action)

- [ ] Kural motoru için abstract altyapı:
  - `ConditionInterface` -> `isSatisfiedBy(Cart $cart): bool`
  - `ActionInterface` -> `applyTo(Cart $cart): void`
- [ ] **Conditions (Şartlar)**:
  - Sepet toplamı X'ten büyük mü?
  - Müşteri VIP grubunda mı? (F32 entegrasyonu)
  - Sepette X kategorisinden Y adet ürün var mı?
  - Bugün Cuma ve saat 20:00 - 24:00 arası mı?
  - Kullanıcı mobil uygulamadan mı giriyor? (F26 entegrasyonu)
  - Müşterinin ilk siparişi mi?
- [ ] **Actions (Eylemler)**:
  - Sepet toplamına %X indirim yap
  - Sepetteki en ucuz ürünü bedava yap
  - X kategorisindeki ürünlere Y TL sabit indirim yap
  - Kargo ücretini sıfırla
  - Z ürününü sepete hediye (0 TL) olarak ekle
- [ ] Birden fazla kuralın öncelik (priority) yönetimi
- [ ] Kural çakışma stratejisi: "Başka kampanyalarla birleştirilemez" (Exclusive) bayrağı

### B. Kupon (Coupon) Modülü

> Kuponlar, aslında arka planda belirli bir "Kupon kodu girildi mi?" şartına (Condition) bağlı kural motoru kayıtlarıdır.

- [ ] `Coupon` entity oluşturuldu (Rule Engine'e bağlı)
- [ ] Kupon özellikleri:
  - `kod` (benzersiz, case-insensitive)
  - Kullanım limiti (toplam ve kişi başı)
  - Başlangıç ve bitiş tarihi
- [ ] Bulk kupon üretme: Affiliate veya influencerlar için tek seferde 1000 adet benzersiz kod üretme (Prefix destekli: `INF-XXXXX`)
- [ ] Kupon kullanım kaydı `kupon_kullanim` tablosuna (Kim, ne zaman, hangi siparişte kullandı?)

### C. Admin API

**Kampanyalar:**
- [ ] `GET /api/v1/admin/kampanyalar`
- [ ] `POST /api/v1/admin/kampanyalar`
- [ ] `PUT /api/v1/admin/kampanyalar/{id}`
- [ ] `DELETE /api/v1/admin/kampanyalar/{id}`

**Kuponlar:**
- [ ] `GET /api/v1/admin/kuponlar`
- [ ] `POST /api/v1/admin/kuponlar`
- [ ] `PUT /api/v1/admin/kuponlar/{id}`
- [ ] `GET /api/v1/admin/kuponlar/{id}/kullanimlar` — Kupon kullanım raporu

### D. Storefront API

- [ ] `POST /api/v1/store/sepet/kupon` — Kupon uygula (F7)
- [ ] `DELETE /api/v1/store/sepet/kupon` — Kaldır

### E. Coupon Abuse Koruması

- [ ] Rate limiting: kupon deneme endpointine
- [ ] Brute force koruması (çok sayıda geçersiz deneme)
- [ ] Şüpheli kupon kullanımı loglama

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Promotion engine çalışıyor
- [ ] Kupon sistemi çalışıyor
- [ ] Kampanya + kupon sepete uygulanıyor
- [ ] Kullanım limitli kupon doğru çalışıyor
- [ ] Abuse koruması var
- [ ] Unit testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

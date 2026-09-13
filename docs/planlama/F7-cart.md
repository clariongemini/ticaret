# F7 — CART (Sepet) DOMAIN

> **Faz:** 7 — Cart  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** YÜKSEK  
> **Bağımlılık:** F5 (Product/Variant), F6 (Inventory)

---

## Amaç

Frontend'e bağımlı olmayan, API-first sepet sistemini oluşturmak. Hem misafir hem de kayıtlı kullanıcı için çalışacaktır.

---

## Görevler

### A. Cart Entity

- [ ] `Cart` entity oluşturuldu
- [ ] Cart alanları:
  - [ ] cart_token (benzersiz, guest için)
  - [ ] musteri_id (nullable, kayıtlı kullanıcı için)
  - [ ] kanal_id (channel)
  - [ ] para_birimi
  - [ ] dil
  - [ ] durum (active / abandoned / converted)
  - [ ] expires_at
- [ ] `CartItem` entity oluşturuldu
- [ ] CartItem alanları:
  - [ ] varyant_id
  - [ ] adet
  - [ ] ozel_secenekler (custom options — JSON)
  - [ ] secilen_ozellikler (selected attributes)
  - [ ] fiyat_anlık (price snapshot — anlık fiyat)
  - [ ] vergi_anlık (tax snapshot)
  - [ ] indirim_anlık (discount snapshot)

### B. Misafir (Guest) Sepet

- [ ] Token bazlı guest cart mekanizması
- [ ] Cart token cookie/header ile taşınıyor
- [ ] Guest sepet süresi belirlendi (örn. 7 gün)

### C. Kayıtlı Kullanıcı Sepeti

- [ ] Giriş yapıldığında guest cart → customer cart merge
- [ ] Aynı kullanıcının birden fazla cihazda sepeti senkronize

### D. Sepet İşlemleri

- [ ] Sepete ürün ekleme (stok kontrolü ile)
- [ ] Sepetten ürün çıkarma
- [ ] Adet güncelleme
- [ ] Sepet temizleme
- [ ] Sepet özeti hesaplama:
  - [ ] Ara toplam
  - [ ] İndirim tutarı
  - [ ] Vergi tutarı
  - [ ] Kargo tahmini
  - [ ] Genel toplam

### E. Fiyat Güvenliği

- [ ] Sepetteki fiyat her eklemede/güncellemede backend'den hesaplanıyor
- [ ] Frontend'in gönderdiği fiyata güvenilmiyor
- [ ] Fiyat değişikliklerinde sepet uyarısı gösteriliyor

### F. Stok Doğrulaması

- [ ] Sepete ürün eklenirken stok kontrolü yapılıyor
- [ ] Sepet görüntülenirken stok tekrar kontrol ediliyor
- [ ] Stokta olmayan ürün checkout'a geçilemiyor

### G. Promosyon Uygulaması

- [ ] Kupon kodu uygulama
- [ ] Otomatik kampanya uygulaması (Promotion Engine — F13)
- [ ] İndirim geçerliliği kontrolü

### H. API

- [ ] `GET /api/v1/store/sepet` — Sepeti görüntüle
- [ ] `POST /api/v1/store/sepet/ekle` — Ürün ekle
- [ ] `PUT /api/v1/store/sepet/{item_id}` — Adet güncelle
- [ ] `DELETE /api/v1/store/sepet/{item_id}` — Ürün çıkar
- [ ] `DELETE /api/v1/store/sepet` — Sepeti temizle
- [ ] `POST /api/v1/store/sepet/kupon` — Kupon uygula
- [ ] `DELETE /api/v1/store/sepet/kupon` — Kuponu kaldır
- [ ] `POST /api/v1/store/sepet/birlestir` — Guest → kayıtlı merge

### I. Abandoned Cart

- [ ] Abandoned cart tespiti (X saat işlem olmayan sepetler)
- [ ] Abandoned cart event: `CartAbandoned`
- [ ] Gelecekte recovery email için altyapı hazır

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Guest sepet çalışıyor
- [ ] Kayıtlı kullanıcı sepeti çalışıyor
- [ ] Sepet merge çalışıyor
- [ ] Fiyat backend'den hesaplanıyor (frontend'e güvenilmiyor)
- [ ] Stok kontrolü çalışıyor
- [ ] Kupon uygulaması çalışıyor
- [ ] Unit ve feature testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

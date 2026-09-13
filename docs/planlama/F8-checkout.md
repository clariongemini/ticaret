# F8 — CHECKOUT (Ödeme Süreci) DOMAIN

> **Faz:** 8 — Checkout  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** KRİTİK  
> **Bağımlılık:** F7 (Cart), F6 (Inventory), F9 (Payment), F11 (Shipping), F12 (Tax)

---

## Amaç

Checkout State Machine'i tasarlamak ve uygulamak. Stok, fiyat, kampanya, vergi, kargo ve ödeme yöntemi checkout sırasında tekrar doğrulanacaktır.

---

## ⚠️ Kritik Kural

> Frontend'in gönderdiği fiyata **ASLA** güvenme.  
> Tüm hesaplamalar backend'de yapılır ve doğrulanır.

---

## Görevler

### A. Checkout State Machine

- [ ] Checkout state machine tasarlandı:
  ```
  cart → address → shipping → payment → review → confirmation
  ```
- [ ] Her state için geçiş kuralları tanımlandı
- [ ] Geçersiz state transition engellendi
- [ ] Checkout session entity oluşturuldu
- [ ] Checkout session süresi belirlendi

### B. Address (Adres) Adımı

- [ ] Fatura adresi girişi
- [ ] Teslimat adresi girişi
- [ ] Kayıtlı kullanıcının adres listesinden seçimi
- [ ] Misafir için yeni adres girişi
- [ ] Adres doğrulama (gerekli alanlar)
- [ ] Adresin sipariş içinde snapshot olarak saklanması planlandı

### C. Shipping (Kargo) Adımı

- [ ] Checkout sırasında uygun kargo yöntemleri listeleniyor (F11 ile entegre)
- [ ] Seçilen kargo yöntemi checkout session'a kaydediliyor
- [ ] Kargo ücreti backend'de hesaplanıyor

### D. Payment (Ödeme) Adımı

- [ ] Uygun ödeme yöntemleri listeleniyor (F9 ile entegre)
- [ ] Seçilen ödeme yöntemi checkout session'a kaydediliyor
- [ ] Kupon/indirim son kontrolü yapılıyor

### E. Review (Onay) Adımı

- [ ] Tüm checkout verisi özeti gösteriliyor
- [ ] Son doğrulama yapılıyor:
  - [ ] Stok kontrolü (en güncel)
  - [ ] Fiyat kontrolü (anlık)
  - [ ] Kampanya geçerliliği
  - [ ] Vergi hesaplaması
  - [ ] Kargo ücreti
  - [ ] Ödeme yöntemi geçerliliği

### F. Idempotency

- [ ] Checkout/order creation için idempotency key implementasyonu
- [ ] Aynı request tekrar gönderildiğinde ikinci sipariş oluşmuyor
- [ ] `idempotency_anahtarlari` tablosu kullanılıyor

### G. Order Creation (Sipariş Oluşturma)

- [ ] Checkout onaylandığında:
  1. [ ] Stok rezervasyonu tamamlandı (committed)
  2. [ ] Sipariş kaydı oluşturuldu (snapshot'larla)
  3. [ ] Adres snapshot alındı
  4. [ ] Fatura oluşturuldu (F12)
  5. [ ] Ödeme initiate edildi (F9)
  6. [ ] Sepet temizlendi / converted olarak işaretlendi
  7. [ ] OrderCreated event fırlatıldı

### H. API

- [ ] `POST /api/v1/store/checkout/baslat` — Checkout session başlat
- [ ] `GET /api/v1/store/checkout/{token}` — Checkout session durumu
- [ ] `PUT /api/v1/store/checkout/{token}/adres` — Adres kaydet
- [ ] `GET /api/v1/store/checkout/{token}/kargo-secenekleri` — Kargo listesi
- [ ] `PUT /api/v1/store/checkout/{token}/kargo` — Kargo seç
- [ ] `GET /api/v1/store/checkout/{token}/odeme-secenekleri` — Ödeme listesi
- [ ] `PUT /api/v1/store/checkout/{token}/odeme` — Ödeme yöntemi seç
- [ ] `GET /api/v1/store/checkout/{token}/ozet` — Checkout özeti
- [ ] `POST /api/v1/store/checkout/{token}/tamamla` — Siparişi oluştur (idempotent)

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Checkout state machine çalışıyor
- [ ] Her adımda backend doğrulaması yapılıyor
- [ ] Fiyat manipülasyonu mümkün değil
- [ ] Idempotency çalışıyor (aynı request → tek sipariş)
- [ ] Stok reservation checkout'a entegre
- [ ] Integration ve E2E testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

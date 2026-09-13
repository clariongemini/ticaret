# F32 — B2B CORPORATE (Kurumsal Müşteri) Modülü

> **Faz:** 32 — B2B Commerce  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** YÜKSEK  
> **Bağımlılık:** F3 (Auth), F4 (Catalog), F5 (Product), F10 (Order)  
> **Yapıdaki Yeri:** Kat 4 — Müşteri yönetimi ve Fiyatlama kurallarını genişletir  
> **İnşaat Analojisi:** Binanın ticari ofis katları — normal ev sahiplerinden farklı kurallara tabi

---

## Amaç

Sistemin sadece son kullanıcıya (B2C) değil, aynı zamanda firmalara (B2B) toptan satış yapabilmesini sağlamak. Firma hesapları, alt kullanıcılar, kredili satış ve müşteriye özel fiyat listeleri (Tier Pricing) kurgusunu oluşturmak.

---

## Görevler

### A. Kurumsal Hesap (Company) Mimarisi

- [ ] `Company` entity oluşturulması (Firma Adı, Vergi Dairesi, Vergi No)
- [ ] Müşteri (User) ile Company arasında `Many-to-One` ilişki
- [ ] Şirket içi roller (B2B Roles):
  - `Admin`: Şirket bilgilerini günceller, alt kullanıcı açar
  - `Buyer`: Sadece ürünleri sepete ekler ve "Onaya Gönder" yapar
  - `Approver`: Sepeti inceler, onaylar ve ödemeye geçer
- [ ] Şirket adres yönetimi (Kurumsal fatura adresleri)

### B. Açık Hesap ve Kredi Limiti (B2B Payment)

- [ ] Şirketlere `credit_limit` (Kredi Limiti) atanması
- [ ] `available_credit` (Kullanılabilir Kredi) takibi
- [ ] Sipariş esnasında kredi kartı yerine "Açık Hesaptan Düş (Net 30/Net 60)" ödeme seçeneği
- [ ] B2B hesap ekstresi (Account Statement) oluşturma (PDF formatında)

### C. Fiyatlama (Tier Pricing & Price Lists)

- [ ] `PriceList` (Fiyat Listesi) yapısı:
  - Liste A (VIP Toptancılar): %20 indirimli
  - Liste B (Standart Toptancılar): %10 indirimli
- [ ] Şirkete özel fiyat listesi atama
- [ ] Adetli alımlara (Volume) özel indirim: "100 adet alırsan birim fiyatı 10 TL, 500 adet alırsan 8 TL" (F13 Rule Engine entegrasyonu)
- [ ] Sadece giriş yapmış B2B müşterilerin fiyatları görebilmesi ayarı (Katalog Gizleme)

### D. B2B Sipariş Akışı (RFQ - Request For Quote)

- [ ] "Fiyat Teklifi İste" (Request for Quote) butonu
- [ ] Adminin teklifi inceleyip özel bir fiyatla geri göndermesi (Negotiation)
- [ ] Müşterinin gelen özel teklifi doğrudan sepete atıp siparişe çevirmesi
- [ ] Hızlı sipariş ekranı (SKU kodlarını CSV ile yapıştırarak toplu sepet oluşturma)

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Bir şirketin altına birden fazla kullanıcı eklenebiliyor
- [ ] Şirkete özel atanan fiyat listesi sepette doğru çalışıyor
- [ ] Kredi limiti kontrolü yapılıyor (Limit aşılırsa siparişe izin vermiyor)
- [ ] Teklif isteme (RFQ) süreci uçtan uca çalışıyor
- [ ] Hızlı sipariş ekranı ile 100 kalem ürün tek seferde sepete eklenebiliyor

---

_Son güncelleme: -_  
_Sorumlu: -_

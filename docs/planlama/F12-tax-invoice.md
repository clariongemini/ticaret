# F12 — TAX (Vergi) & INVOICE (Fatura) DOMAIN

> **Faz:** 12 — Tax / Invoice  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** YÜKSEK  
> **Bağımlılık:** F10 (Order), F5 (Product)

---

## Amaç

Vergi sistemini hardcoded KDV oranı olmaktan çıkarıp kural bazlı, genişletilebilir bir yapıya kavuşturmak. Faturayı muhasebe belgesi olarak order'dan bağımsız modellemek.

---

## ⚠️ Kritik Kurallar

> - KDV oranları **KESİNLİKLE** kod içine yazılmaz  
> - Fatura order'dan bağımsız bir accounting document olarak modellenmelidir  
> - e-Fatura / e-Arşiv entegrasyonuna hazır adapter architecture  
> - Floating point para birimi kullanılmaz

---

## Görevler

### A. Tax (Vergi) Sistemi

- [ ] `TaxClass` entity oluşturuldu (örn: standart, indirimli, muaf)
- [ ] `TaxRate` entity oluşturuldu (oran + ülke + bölge bazlı)
- [ ] `TaxRule` entity oluşturuldu (class + rate + country + region eşleştirmesi)
- [ ] Vergi hesaplama servisi:
  - [ ] Tax included / excluded price desteği
  - [ ] Ülke/bölge bazlı vergi
  - [ ] Ürün kategori/tip bazlı vergi sınıfı
- [ ] Vergi Admin panel yönetimi
- [ ] Türkiye KDV oranları veri olarak girildi (%0, %1, %10, %20)
- [ ] Gelecekte yeni ülke vergisi eklemek için core kod değişmiyor

### B. Invoice (Fatura) Sistemi

- [ ] `Invoice` entity oluşturuldu (order'dan bağımsız)
- [ ] Fatura alanları:
  - [ ] `fatura_no` (seri + numara)
  - [ ] `fatura_tarihi`
  - [ ] `musteri_snapshot` (müşteri bilgisi anlık kaydı)
  - [ ] `fatura_adresi_snapshot`
  - [ ] `kalemler` (items snapshot)
  - [ ] `ara_toplam`
  - [ ] `vergi_tutari`
  - [ ] `indirim_tutari`
  - [ ] `genel_toplam`
  - [ ] `para_birimi`
- [ ] Fatura numarası seri sistemi (yıl bazlı reset)
- [ ] PDF fatura üretimi (queue üzerinden)
- [ ] Fatura email ile gönderimi

### C. e-Fatura / e-Arşiv Hazırlığı

- [ ] Adapter architecture oluşturuldu
- [ ] `EInvoiceAdapterInterface` tanımlandı (gelecek entegrasyon için)
- [ ] GIB uyumlu XML format çıktısına hazırlık [TO VERIFY - güncel GIB gereksinimleri]

### D. Admin API

**Vergi:**
- [ ] `GET /api/v1/admin/vergi-siniflari` — Vergi sınıfı listesi
- [ ] `POST /api/v1/admin/vergi-siniflari` — Oluştur
- [ ] `GET /api/v1/admin/vergi-oranlari` — Vergi oranı listesi
- [ ] `POST /api/v1/admin/vergi-oranlari` — Oluştur

**Fatura:**
- [ ] `GET /api/v1/admin/faturalar` — Fatura listesi
- [ ] `GET /api/v1/admin/faturalar/{id}` — Fatura detayı
- [ ] `GET /api/v1/admin/faturalar/{id}/pdf` — PDF indir

### E. Storefront API

- [ ] `GET /api/v1/store/siparislerim/{id}/fatura` — Fatura görüntüle
- [ ] `GET /api/v1/store/siparislerim/{id}/fatura/pdf` — Fatura PDF

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Vergi sınıfı ve oranları admin panelden yönetiliyor
- [ ] KDV hesaplama doğru çalışıyor
- [ ] Fatura order'dan bağımsız oluşturuluyor
- [ ] PDF fatura üretimi çalışıyor
- [ ] e-Fatura adapter interface tanımlandı
- [ ] Unit testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

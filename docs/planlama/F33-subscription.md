# F33 — SUBSCRIPTION (Abonelik ve Düzenli Sipariş)

> **Faz:** 33 — Subscription  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** ORTA  
> **Bağımlılık:** F9 (Payment), F10 (Order), F5 (Product)  
> **Yapıdaki Yeri:** Kat 4 — Sipariş akışının tekrarlanan hali  
> **İnşaat Analojisi:** Binanın düzenli aidat toplama sistemi

---

## Amaç

Müşterilerin filtre kahve, kedi maması, lens solüsyonu gibi düzenli tükettikleri ürünlere "Abonelik" başlatabilmesini sağlamak. Sistemin arka planda (Cron/Scheduler ile) belirlenen periyotlarda otomatik ödeme alıp yeni sipariş oluşturmasını sağlamak.

---

## Görevler

### A. Ürün Abonelik Ayarları (Product Level)

- [ ] Ürün modeline `is_subscribable` (Abone olunabilir) bayrağı ekleme
- [ ] Abonelik periyotlarının belirlenmesi (Örn: 1 Hafta, 2 Hafta, 1 Ay, 3 Ay)
- [ ] Abonelik teşviki: "Tek seferlik al 100 TL, Her ay gönder 90 TL (%10 İndirim)" (F13 Rule Engine ile entegre)

### B. Müşteri Abonelik Yönetimi (Customer Level)

- [ ] `Subscription` entity oluşturulması
- [ ] Abonelik durumları: `Active`, `Paused`, `Cancelled`, `Past_Due` (Ödeme alınamadı)
- [ ] Müşteri panelinde (My Account) abonelik yönetimi:
  - Bir sonraki teslimat tarihini erteleme (Skip next delivery)
  - Adres/Kredi kartı güncelleme
  - Aboneliği iptal etme / duraklatma

### C. Ödeme Tokenizasyonu (Billing)

- [ ] PCI-DSS kuralları gereği Iyzico/Stripe gibi sağlayıcılardan dönen "Card Token" üzerinden arka planda otomatik para çekme (Card Storage altyapısı)
- [ ] Kredi kartı limiti yetersizse veya son kullanma tarihi geçmişse:
  - Aboneliği `Past_Due` durumuna çekme
  - F29 (Notification) üzerinden müşteriye "Ödeme alınamadı, lütfen kartınızı güncelleyin" bildirimi (SMS/Email/WhatsApp) atma

### D. Arkaplan İşlemleri (Scheduler)

- [ ] Günlük çalışan (Midnight) Cron Job:
  - O gün yenilenmesi gereken tüm aktif abonelikleri bulur.
  - İlgili tutarı tahsil eder (F9 Payment).
  - Ödeme başarılıysa yepyeni bir "Order" (F10) oluşturur ve kargoya aktarır (F12).

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Ürün sayfasında "Abonelik" seçeneği görülebiliyor
- [ ] Abone olunan ürünler için sistem belirtilen tarihte insan müdahalesi olmadan otomatik ödeme çekip sipariş üretiyor
- [ ] Kredi kartı çekilemediğinde sistem çökmeden uyarı veriyor ve 3 gün boyunca tekrar deniyor (Retry mechanism)
- [ ] Müşteri kendi panelinden aboneliğini durdurabiliyor

---

_Son güncelleme: -_  
_Sorumlu: -_

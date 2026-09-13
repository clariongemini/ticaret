# F34 — WEBHOOKS & APP INTEGRATION (Dışa Aktarım)

> **Faz:** 34 — Webhooks Out  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** ORTA  
> **Bağımlılık:** F19 (Plugin System), F10 (Order)  
> **Yapıdaki Yeri:** Kat 7 — Dış dünyaya (3. parti yazılımlara) veri çıkışı sağlar  
> **İnşaat Analojisi:** Binanın dışarıya veri sızdıran akıllı sensör ağı

---

## Amaç

Sistemin "Kapalı Kutu" (Silo) olmasını engellemek. Mağaza sahiplerinin (satıcıların) kendi kullandıkları muhasebe yazılımlarına (Logo, Paraşüt), CRM sistemlerine (HubSpot) veya otomasyon platformlarına (Zapier, Make.com) sistemimizdeki anlık değişiklikleri (Event) iletebilmesini sağlamak.

---

## Görevler

### A. Webhook Engine

- [ ] `Webhook` entity oluşturulması (URL, Secret Key, Hangi Eventler, Aktif/Pasif)
- [ ] Sistemdeki kritik Event'lerin dışarıya açılması (Exposed Events):
  - `order.created` (Yeni Sipariş)
  - `order.status_changed` (Sipariş Durumu Değişti)
  - `customer.registered` (Yeni Müşteri)
  - `product.stock_depleted` (Stok Tükendi)
- [ ] Asenkron gönderim: Webhooklar asla senkron (API'yi bloke edecek şekilde) çalışmaz. F19 Event Bus üzerinden Laravel Queue'ya atılır ve arkaplanda dış sisteme POST edilir.

### B. Güvenlik ve Payload

- [ ] Webhook Payload yapısı (Standardize edilmiş JSON formatı)
- [ ] Güvenlik İmzası (HMAC Signature): Giden isteğin header'ına (`X-Store-Signature`), body'nin secret key ile oluşturulmuş SHA-256 hash'i eklenir. Böylece dış sistem verinin bizden geldiğini doğrular.
- [ ] Webhook Timeout süresi (Örn: 5 saniye bekle, cevap gelmezse düşür).

### C. Hata Yönetimi (Retry & Dead-Letter)

- [ ] Dış sistem (Örn: Müşterinin kendi sunucusu) çöktüğünde webhook'u tekrar deneme stratejisi (Exponential Backoff: 1dk, 5dk, 1 saat, 24 saat sonra tekrar dene).
- [ ] `webhook_logs` tablosu: Tüm giden istekler, gelen cevaplar (HTTP 200, 500 vb.) ve payload'lar loglanır (Sorun giderme için admin panele sunulur).
- [ ] Art arda X kez başarısız olan webhook'u otomatik olarak "Pasif" (Disabled) duruma alma ve satıcıya bildirim gönderme.

### D. Zapier / Make.com / Shopify API Uyumluluğu

- [ ] Webhook yapısının Zapier'in standart Catch Hook yapısıyla tam uyumlu çalışması.
- [ ] İsteğe bağlı olarak "Sipariş verisini Shopify'ın beklediği formatta (Transformer) gönder" gibi adapter (adaptör) desteği.

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Admin panelinden yeni bir webhook URL'i kaydedilebiliyor
- [ ] Sipariş oluşturulduğunda sistem anında o URL'e JSON verisini POST ediyor
- [ ] Başarısız giden webhook belli aralıklarla tekrar deneniyor
- [ ] Webhook logları arayüzde incelenebiliyor

---

_Son güncelleme: -_  
_Sorumlu: -_

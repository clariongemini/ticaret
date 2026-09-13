# F26 — MOBİL UYGULAMA ENTEGRASYON ALT YAPISI

> **Faz:** 26 — Mobile Integration  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** YÜKSEK  
> **Bağımlılık:** F18 (API), F3 (Auth), F10 (Order), F9 (Payment)  
> **İnşaat Analojisi:** Bina hazır, mobil araç erişim yolu ve garaj bağlantısı

---

## Amaç

Mevcut commerce API üzerine, mobil uygulamanın ihtiyaç duyduğu ek altyapıyı kurmak. Web ve mobil **aynı backend'i** kullanır — bu faz mobil'e özel gereksinimleri karşılar.

---

## ⚠️ Temel Kural

> Mobil uygulama için ayrı bir backend **ASLA** oluşturulmaz.  
> Her özellik API-first tasarlanmıştır zaten.  
> Bu faz **mobil'e özel ek katmanları** ekler.

---

## Görevler

### A. Mobil Auth Genişletmesi

- [ ] **Refresh token rotasyonu** mobil için optimize edildi:
  - Uzun süreli refresh token (30 gün) — mobil kullanıcı her açışta login olmayacak
  - Web için: 7 gün
  - Mobil için: 30 gün (configurable)
- [ ] **Device yönetimi:**
  - Her cihaz için kayıt: device_token, platform (ios/android), model, os_version, app_version
  - Kullanıcı hangi cihazlardan giriş yaptı listesi (account security)
  - Cihaz bazlı token revocation
- [ ] **Biometrik auth altyapısı:**
  - Biometrik auth sunucu tarafında özel mekanizma gerektirmez
  - Native tarafta biometrik unlock → local token kullanımı
  - API: `POST /api/v1/mobile/biometric/verify` (local token ile sunucu doğrulaması)
- [ ] **Guest kullanıcı → kayıtlı kullanıcı geçişi** mobil için optimize

---

### B. Push Notification Altyapısı

- [ ] **Push provider abstraction** (F19 Plugin sistemiyle entegre):
  - `PushNotificationProviderInterface`:
    - `sendToDevice(device_token, title, body, data)`
    - `sendToTopic(topic, title, body, data)`
    - `sendToSegment(segment, title, body, data)`
  - FCM (Firebase Cloud Messaging) implementasyonu
  - APNS (Apple Push Notification Service) implementasyonu
- [ ] `cihaz_push_tokenlari` tablosu:
  - musteri_id, device_id, push_token, platform, aktif, son_kullanim
- [ ] **Notification event'leri mobil için genişletildi:**
  - OrderCreated → push: "Siparişiniz alındı 🎉"
  - OrderShipped → push: "Siparişiniz kargoya verildi 📦"
  - OrderDelivered → push: "Siparişiniz teslim edildi ✅"
  - PaymentFailed → push: "Ödemeniz başarısız — tekrar deneyin"
  - LowStockAlert → push (favorilenen ürün için): "İzlediğiniz ürün tükeniyor!"
  - PriceDropAlert → push: "İzlediğiniz ürünün fiyatı düştü 🏷️"
  - BackInStockAlert → push: "Beklediğiniz ürün tekrar stokta!"
- [ ] Admin panelden push notification gönderimi (broadcast)
- [ ] Kullanıcının push tercihlerini yönetme (her bildirim tipi ayrı açma/kapama)

---

### C. Deep Link Şeması

- [ ] **Universal Links (iOS) / App Links (Android) altyapısı:**
  - `/.well-known/apple-app-site-association` endpoint (iOS)
  - `/.well-known/assetlinks.json` endpoint (Android)
- [ ] **Deep link URL şeması:**
  ```
  Ürün: https://site.com/urunler/{slug} → app://urunler/{id}
  Kategori: https://site.com/kategoriler/{slug} → app://kategoriler/{id}
  Sipariş: https://site.com/siparislerim/{no} → app://siparislerim/{no}
  Kampanya: https://site.com/kampanya/{code} → app://kampanya/{code}
  Blog: https://site.com/blog/{slug} → app://blog/{slug}
  ```
- [ ] Deep link → uygulama yüklü değilse web sayfasına fallback
- [ ] Email/SMS içindeki linkler deep link şemasına uyuyor

---

### D. Google App Indexing

- [ ] Ürün ve kategori sayfaları Google App Indexing uyumlu
- [ ] `<link rel="alternate" ...>` tag'leri web sayfalarında
- [ ] Firebase App Indexing SDK entegrasyon rehberi hazırlandı

---

### E. App Version Kontrolü

- [ ] `GET /api/v1/mobile/version` endpoint:
  ```json
  {
    "ios": {"min_version": "2.0.0", "current_version": "2.3.1", "force_update": false},
    "android": {"min_version": "2.0.0", "current_version": "2.3.1", "force_update": false}
  }
  ```
- [ ] Admin panelden min_version ve force_update ayarı
- [ ] Force update: uygulama açıldığında güncelleme zorunlu ekranı gösterilmesi

---

### F. Offline-First Cart Sync

- [ ] `GET /api/v1/store/sepet` → çevrimdışıyken local cache kullanılır (native tarafta)
- [ ] Bağlantı geldiğinde yerel sepet → sunucu sepet merge
- [ ] Conflict resolution: sunucu yetkili (stok/fiyat değişmişse native günceller)
- [ ] API response'a cache-friendly header eklendi: `Cache-Control: private, max-age=300`

---

### G. Mobil Özel API Optimizasyonları

- [ ] **Composite endpoint'ler** (birden fazla API çağrısını birleştiren):
  - `GET /api/v1/mobile/ana-sayfa` → banner + featured ürünler + kampanyalar tek seferde
  - `GET /api/v1/mobile/urun/{id}` → ürün + varyant + stok + SEO + öneriler tek seferde
  - `GET /api/v1/mobile/profil` → müşteri + son siparişler + favoriler tek seferde
- [ ] API response boyutu optimize edildi (gereksiz alanlar mobil response'dan çıkarıldı)
- [ ] Görsel URL'leri mobil boyutlu thumbnail döndürüyor (webp, 400x400)
- [ ] `User-Agent` header bazlı response optimizasyonu

---

### H. Mobil Analytics Events

- [ ] `POST /api/v1/mobile/events` — first-party event toplama endpoint:
  ```json
  {"event": "product_viewed", "product_id": "...", "timestamp": "..."}
  ```
- [ ] Mobil event'ler: app_opened, product_viewed, add_to_cart, checkout_started, search_performed
- [ ] Bu veriler F27 öneri motoru için kullanılacak

---

### I. Mobil Ödeme Entegrasyonları

- [ ] **Apple Pay** hazırlığı (gateway plugin genişletmesi)
- [ ] **Google Pay** hazırlığı (gateway plugin genişletmesi)
- [ ] Mobil ödeme akışı: native token → backend payment API

---

### J. Rate Limiting Mobil için Ayarlama

- [ ] Mobil `User-Agent` veya `X-Client-Type: mobile` header'ı tanındı
- [ ] Mobil için ayrı rate limit profili:
  - Fotoğraf yükleme: 5/dakika (büyük payload)
  - Search: 200/dakika (mobil sık sık arar)
  - Push token güncelleme: 10/gün

---

### K. Güvenlik — Mobil Özel

- [ ] Certificate pinning rehberi (native uygulama için)
- [ ] API anahtarı mobile embed edilmez (OAuth2 + PKCE)
- [ ] Jailbreak/root detection → yüksek riskli işlemlerde uyarı (native taraf)
- [ ] App Store / Play Store yayın güvenlik kontrol listesi

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Push notification altyapısı çalışıyor (FCM + APNS)
- [ ] Deep link endpoint'leri hazır
- [ ] App version kontrolü çalışıyor
- [ ] Mobil composite endpoint'ler çalışıyor
- [ ] Offline cart sync test edildi
- [ ] Mobil auth (uzun refresh token + device yönetimi) çalışıyor
- [ ] Integration testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

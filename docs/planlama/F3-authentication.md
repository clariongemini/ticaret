# F3 — AUTHENTICATION & AUTHORIZATION

> **Faz:** 3 — Auth  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** KRİTİK  
> **Bağımlılık:** F1, F2

---

## Amaç

API-first authentication sistemi kurmak. Web, mobile ve admin aynı auth altyapısını kullanacaktır.

---

## Görevler

### A. Customer Authentication (Storefront)

- [ ] Kayıt (register) endpoint'i — `/api/v1/store/auth/register`
- [ ] Giriş (login) endpoint'i — `/api/v1/store/auth/login`
- [ ] Çıkış (logout) endpoint'i — `/api/v1/store/auth/logout`
- [ ] Token yenileme (refresh token) endpoint'i
- [ ] Şifre sıfırlama akışı (forgot / reset password)
- [ ] Email doğrulama akışı
- [ ] MFA (Multi-Factor Authentication) altyapısı (ileride aktif edilmek üzere)

### B. Admin Authentication

- [ ] Admin giriş endpoint'i — `/api/v1/admin/auth/login`
- [ ] Admin çıkış endpoint'i
- [ ] Admin MFA (zorunlu)
- [ ] Session timeout mekanizması
- [ ] IP/risk bazlı giriş izleme
- [ ] Başarısız giriş denemesi loglama ve kilitleme

### C. Token Yönetimi

- [ ] Access token süresi belirlendi
- [ ] Refresh token rotation implementasyonu
- [ ] Token revocation (blacklist) mekanizması
- [ ] Device management — hangi cihazlardan giriş yapıldığı
- [ ] Token'da store/channel scope
- [ ] `musteriler`, `kullanici_oturumlari`, `kullanici_cihazlari` tabloları kullanılıyor

### D. RBAC (Role-Based Access Control)

- [ ] Permission sistemi tanımlandı (products.view, products.create, products.update, products.delete, orders.view, orders.update, orders.refund vb.)
- [ ] Roller tanımlandı:
  - [ ] Super Admin
  - [ ] Store Admin
  - [ ] Catalog Manager
  - [ ] Order Manager
  - [ ] Inventory Manager
  - [ ] Finance Manager
  - [ ] Content Manager
  - [ ] SEO Manager
  - [ ] Customer Support
  - [ ] Marketing Manager
- [ ] Role ↔ Permission ilişkileri kuruldu
- [ ] Middleware / Policy / Gate oluşturuldu
- [ ] Store/tenant scope RBAC'a eklendi

### E. Guest Kullanıcı Desteği

- [ ] Guest session token mekanizması
- [ ] Guest → kayıtlı kullanıcı merge (sepet, wishlist)

### F. OAuth2 / Sosyal Giriş (Opsiyonel - Gelecek)

- [ ] Yapı OAuth2'ye hazır bırakıldı
- [ ] Social login için extension point bırakıldı (Google, Facebook vb.)

### G. Rate Limiting (Auth Özelinde)

- [ ] Login endpoint'i rate limit: IP başına X istek/dakika
- [ ] Register endpoint'i rate limit
- [ ] Password reset rate limit
- [ ] Brute force koruma mekanizması

### H. Güvenlik

- [ ] Şifreler bcrypt ile hash'lendi
- [ ] Token'lar SHA-256 hash ile saklandı
- [ ] Session fixation koruması
- [ ] CSRF koruması (web form için)
- [ ] Credential stuffing koruması

### I. API Güvenlik Katmanları

- [ ] Public endpointler belirlendi
- [ ] Authenticated endpointler belirlendi
- [ ] Admin endpointler belirlendi
- [ ] Internal endpointler belirlendi
- [ ] Webhook endpointler belirlendi
- [ ] Her endpoint için authorization policy tanımlandı

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Storefront auth çalışıyor
- [ ] Admin auth çalışıyor
- [ ] RBAC sistemi çalışıyor
- [ ] Token rotation çalışıyor
- [ ] Rate limiting çalışıyor
- [ ] Auth testleri yazıldı
- [ ] IDOR testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

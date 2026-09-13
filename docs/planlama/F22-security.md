# F22 — SECURITY HARDENING (Güvenlik Güçlendirme)

> **Faz:** 22 — Security  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** KRİTİK  
> **Bağımlılık:** Tüm fazlar tamamlandıktan sonra kapsamlı review

---

## Amaç

OWASP ASVS / OWASP Top 10 prensiplerini uygulayarak platformu production güvenlik standartlarına taşımak.

---

## Görevler

### A. OWASP Top 10 Kontrolleri

- [ ] **SQL Injection:** Tüm database sorguları parameterized query / ORM ile yapılıyor
- [ ] **XSS:** Output escaping her yerde aktif; CSP header uygulandı
- [ ] **CSRF:** Form/state-changing işlemlerde CSRF token; SameSite cookie
- [ ] **SSRF:** Harici URL fetch işlemleri allowlist ile kısıtlandı
- [ ] **IDOR:** Her data erişiminde ownership kontrolü var; IDOR testleri geçti
- [ ] **Broken Access Control:** RBAC tüm admin endpoint'lerinde çalışıyor
- [ ] **Mass Assignment:** Model fillable list kısıtlandı; request validation var
- [ ] **Session Fixation:** Login sonrası session yenileniyor
- [ ] **Credential Stuffing:** Rate limiting + account lockout aktif
- [ ] **Brute Force:** Login denemesi limiti + geçici kilitleme
- [ ] **File Upload:** Tip kontrolü, boyut limiti, depolama path isolation
- [ ] **Webhook Forgery:** Signature verification zorunlu
- [ ] **Replay Attack:** Webhook idempotency + timestamp kontrolü
- [ ] **Race Condition:** Inventory ve payment için test edildi

### B. Secrets & Credentials

- [ ] API key / secret / payment credential source code'da YOK
- [ ] `.env` dosyası `.gitignore`'da
- [ ] Admin panelde secret masked gösteriliyor
- [ ] Production'da secret rotation mekanizması planlandı

### C. KVKK / Data Protection

- [ ] Consent management çalışıyor
- [ ] Kullanıcı veri silme/export talebi akışı var
- [ ] Marketing email/SMS iznine göre gönderim
- [ ] Cookie consent yönetimi
- [ ] Kart bilgisi core sistemde saklanmıyor

### D. Security Headers

- [ ] `Content-Security-Policy` (CSP) — 3DS/payment uyumlu
- [ ] `Strict-Transport-Security` (HSTS)
- [ ] `X-Content-Type-Options: nosniff`
- [ ] `X-Frame-Options` veya `frame-ancestors`
- [ ] `Referrer-Policy`
- [ ] `Permissions-Policy`

### E. Admin Security

- [ ] MFA zorunlu (admin kullanıcılar)
- [ ] Session timeout: X dakika inaktivite
- [ ] Failed login monitoring ve alert
- [ ] Admin panel'e IP whitelist (opsiyonel)
- [ ] Audit log: tüm kritik işlemler kayıt altında

### F. Fraud Prevention (Gelecek Hazırlığı)

- [ ] Extension point bırakıldı:
  - [ ] IP risk sinyali
  - [ ] Device fingerprint
  - [ ] Velocity check (aynı IP'den çok sipariş)
  - [ ] Address mismatch tespiti
  - [ ] Payment response anomali

### G. Security Threat Model Belgesi

- [ ] Her senaryo analiz edildi:
  - [ ] Sahte fiyat manipülasyonu
  - [ ] Stok race condition
  - [ ] Payment webhook forgery
  - [ ] Duplicate order
  - [ ] Duplicate payment
  - [ ] Refund abuse
  - [ ] Coupon abuse
  - [ ] IDOR
  - [ ] Admin privilege escalation
  - [ ] API scraping / veri kazıma
  - [ ] Bot checkout
  - [ ] Credential stuffing
  - [ ] Malicious file upload
- [ ] Her tehdit için: RISK / MİTİGASYON / TEST tanımlandı

### H. Penetration Test Kontrol Listesi

- [ ] Tüm endpoint'lere unauthenticated erişim denenip reddedildi
- [ ] IDOR: başka kullanıcının verisi erişim denemesi engellendi
- [ ] Admin'e müşteri token ile erişim engellendi
- [ ] Rate limit testi yapıldı
- [ ] Webhook imzasız request reddedildi

---

## Çıkış Kriteri (Exit Criteria)

- [ ] OWASP Top 10 kontrolleri tamamlandı
- [ ] Security threat model belgelendi
- [ ] Security testleri tüm geçiyor
- [ ] IDOR testleri geçiyor
- [ ] Audit log çalışıyor
- [ ] Secret'lar kod içinde yok
- [ ] KVKK compliance kontrol edildi
- [ ] Security header'lar aktif

---

_Son güncelleme: -_  
_Sorumlu: -_

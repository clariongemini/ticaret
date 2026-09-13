# F24 — PRODUCTION DEPLOYMENT

> **Faz:** 24 — Deployment  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** YÜKSEK  
> **Bağımlılık:** F21 (Testing), F22 (Security), F23 (Performance)

---

## Amaç

Production ortamını sıfır downtime ile çalıştıracak deployment mimarisini ve disaster recovery planını oluşturmak.

---

## Görevler

### A. Ortam Stratejisi

- [ ] Ortamlar tanımlandı:
  - [ ] `local` — Geliştirici makinesi
  - [ ] `development` — Paylaşılan dev sunucu
  - [ ] `staging` — Production'a eş test ortamı
  - [ ] `production` — Canlı sistem
- [ ] Payment sandbox production'dan **kesinlikle ayrı**
- [ ] Her ortam için ayrı `.env` konfigürasyonu
- [ ] Staging ortamı production verisi içermiyor

### B. Zero-Downtime Deployment

- [ ] Migration stratejisi (expand/contract pattern):
  - Adım 1: Yeni kolonu ekle (eski hala çalışır)
  - Adım 2: Uygulamayı deploy et
  - Adım 3: Eski kolonu kaldır (ayrı migration)
- [ ] Blue/green veya rolling deployment değerlendirildi
- [ ] Queue worker graceful shutdown
- [ ] Deployment checklist oluşturuldu

### C. Migration Güvenliği

- [ ] Migration'lar version controlled
- [ ] Production'a manuel SQL uygulaması yok
- [ ] Destructive migration'lar için safety gate (onay mekanizması)
- [ ] Migration rollback stratejisi belirlendi

### D. Backup Stratejisi

- [ ] Database yedekleme:
  - [ ] Günlük tam yedek
  - [ ] Saatlik incremental yedek
  - [ ] Point-in-time recovery (PITR) değerlendirildi
- [ ] Yedek depolama: coğrafi olarak ayrı lokasyon
- [ ] Yedek şifreleme
- [ ] Yedek restore testi (aylık yapılmalı)

### E. Disaster Recovery

- [ ] RPO hedefi belirlendi: X saat (tolere edilebilir veri kaybı)
- [ ] RTO hedefi belirlendi: X saat (tolere edilebilir kesinti süresi)
- [ ] Failure senaryoları ve yanıt planları:
  - [ ] Database geçici kesintisi
  - [ ] Redis/cache kesintisi
  - [ ] Queue kesintisi
  - [ ] Payment provider kesintisi
  - [ ] Shipping provider kesintisi
  - [ ] Email/SMS sağlayıcı kesintisi
  - [ ] CDN kesintisi
  - [ ] Search engine kesintisi
  - [ ] Merchant feed üretim başarısızlığı
  - [ ] Webhook gecikmesi/duplicatı
- [ ] Her senaryo için: sistemin davranışı + fallback + recovery adımları

### F. CI/CD Pipeline

- [ ] CI pipeline:
  - [ ] Kod push → otomatik testler
  - [ ] PHPStan static analysis
  - [ ] Code style (PHP-CS-Fixer)
  - [ ] Security dependency check
- [ ] CD pipeline:
  - [ ] staging otomatik deploy
  - [ ] production manuel onay ile deploy
- [ ] Deployment sonrası smoke test

### G. Infrastructure

- [ ] Web server konfigürasyonu (Nginx/Caddy)
- [ ] PHP-FPM konfigürasyonu
- [ ] MySQL konfigürasyonu (indexler, query cache)
- [ ] Redis konfigürasyonu
- [ ] Queue worker supervisor konfigürasyonu
- [ ] SSL/TLS sertifikası (auto-renewal)
- [ ] Firewall kuralları

### H. Config Validation

- [ ] Production başlamadan önce check:
  - [ ] Database bağlantısı
  - [ ] Payment API credentials
  - [ ] Mail server bağlantısı
  - [ ] Storage bağlantısı
  - [ ] Queue bağlantısı
  - [ ] Cache bağlantısı
  - [ ] Security key'ler tanımlı

### I. Deployment Dokümantasyonu

- [ ] `docs/DEPLOYMENT.md` yazıldı
- [ ] İlk kurulum adımları
- [ ] Production checklist
- [ ] Rollback prosedürü
- [ ] Incident response planı

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Production ortamı hazır
- [ ] Zero-downtime deployment test edildi
- [ ] Backup çalışıyor ve restore test edildi
- [ ] RPO/RTO hedefleri belirlendi
- [ ] CI/CD pipeline çalışıyor
- [ ] Config validation health check çalışıyor
- [ ] Deployment dokümantasyonu tamamlandı
- [ ] Disaster recovery planı yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

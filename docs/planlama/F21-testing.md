# F21 — TEST STRATEGY (Test Mimarisi)

> **Faz:** 21 — Testing  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** KRİTİK — Her fazla paralel yürütülür  
> **Bağımlılık:** Tüm fazlar

---

## Amaç

Modül "kod yazıldı" diye değil, test edildi, güvenli ve edge case'leri kapsıyor diye DONE sayılır.

---

## Görevler

### A. Unit Tests (Birim Testler)

- [ ] Domain entity testleri
- [ ] Value Object testleri
- [ ] Domain Service testleri
- [ ] Application Service testleri
- [ ] Promotion Engine testleri
- [ ] Price calculation testleri
- [ ] Tax calculation testleri
- [ ] Shipping rule engine testleri
- [ ] Inventory calculation testleri (physical - reserved = available)

### B. Integration Tests

- [ ] Repository implementation testleri
- [ ] Database query testleri
- [ ] Queue job testleri
- [ ] Cache testleri
- [ ] Event dispatcher testleri

### C. Feature / API Tests

- [ ] Tüm storefront API endpoint'leri
- [ ] Tüm admin API endpoint'leri
- [ ] Authentication akışları
- [ ] Yetkilendirme (authorization) testleri
- [ ] Hata senaryoları (400, 401, 403, 404, 422, 429, 500)

### D. Contract Tests

- [ ] OpenAPI spec ile API yanıtı uyumu testi
- [ ] Storefront ↔ Backend contract
- [ ] Mobile ↔ Backend contract

### E. Payment Tests

- [ ] Her gateway için test suite:
  - [ ] Başarılı ödeme
  - [ ] Başarısız ödeme
  - [ ] Timeout
  - [ ] 3DS başarılı / başarısız
  - [ ] Duplicate webhook
  - [ ] Gecikmiş webhook
  - [ ] Full refund
  - [ ] Partial refund
- [ ] Fake gateway ile test (sandbox bağımlılığı yok)

### F. Webhook Tests

- [ ] Signature verification testi
- [ ] Idempotency testi (aynı event 2 kez → 1 işlem)
- [ ] Replay protection testi
- [ ] Webhook retry testi

### G. Concurrency Tests (ZORUNLU)

- [ ] **Kritik test:** stok = 1 iken 100 eş zamanlı satın alma
  - Beklenen: sadece 1 başarılı order
  - Beklenen: negatif stok oluşmaz
- [ ] Race condition testi: aynı kupon aynı anda 2 kullanıcı
- [ ] Race condition testi: çift ödeme engeli

### H. Security Tests

- [ ] IDOR testleri (başka müşterinin siparişine erişim denemesi)
- [ ] Price manipulation testi (frontend'den sahte fiyat gönderme)
- [ ] SQL Injection testleri
- [ ] XSS testleri
- [ ] CSRF testleri
- [ ] Rate limit testleri (brute force)
- [ ] Mass assignment testleri
- [ ] Yetki yükseltme (privilege escalation) testi

### I. Inventory Tests

- [ ] Reservation timeout testi
- [ ] Multi-warehouse allocation testi
- [ ] Bundle stok hesap testi
- [ ] Stok reconciliation testi

### J. E2E Tests

- [ ] Tam satın alma akışı (product → cart → checkout → payment → order)
- [ ] Guest kullanıcı alışveriş akışı
- [ ] Kayıtlı kullanıcı akışı
- [ ] İade akışı
- [ ] Admin sipariş yönetimi akışı

### K. Performance Tests

- [ ] 100 eş zamanlı kullanıcı senaryosu
- [ ] 1.000 eş zamanlı kullanıcı senaryosu
- [ ] 10.000 ürün kataloğu query performansı
- [ ] 100.000 ürün kataloğu query performansı
- [ ] Yüksek trafikli ürün sayfası (cache testi)
- [ ] Checkout bottleneck analizi

### L. Migration Tests

- [ ] Fresh migration testi
- [ ] Migration rollback testi

### M. Test Ortamı

- [ ] Fake payment gateway kuruldu
- [ ] Fake email/SMS provider kuruldu
- [ ] Test database izolasyonu
- [ ] Factory sınıfları tüm entity'ler için yazıldı
- [ ] Seeder'lar gerçekçi test verisi üretiyor

---

## Definition of Done (Tamamlanma Kriterleri)

Bir modül ancak şunların tamamı tamamlandığında **DONE** sayılır:

- [ ] Implementation tamamlandı
- [ ] Unit testleri yazıldı ve geçiyor
- [ ] Integration testleri yazıldı ve geçiyor
- [ ] Security testleri yazıldı ve geçiyor
- [ ] Dokümantasyon güncellendi
- [ ] Migration var ve çalışıyor
- [ ] API Contract tanımlandı
- [ ] Error handling tamamlandı
- [ ] Observability eklendi (log + metric)
- [ ] Performance kabul edilebilir
- [ ] Edge case'ler ele alındı

---

_Son güncelleme: -_  
_Sorumlu: -_

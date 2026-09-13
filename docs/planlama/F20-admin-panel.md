# F20 — ADMIN PANEL

> **Faz:** 20 — Admin Panel  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** YÜKSEK  
> **Bağımlılık:** F3 (Auth/RBAC), F4-F17 (Tüm domain'ler)

---

## Amaç

Operasyon personelinin kolayca kullanabileceği, API tabanlı bir admin arayüzü oluşturmak.

---

## Notlar

> Admin panel bir frontend uygulaması olabilir (React/Vue/Inertia) veya framework'ün admin paketleri kullanılabilir.  
> Her halükarda admin UI, API/Domain layer üzerinden çalışır. Business logic admin controller'a YAZILABİLİR.

---

## Görevler

### A. Dashboard

- [ ] Ana dashboard metrikleri:
  - [ ] Günlük/haftalık/aylık satış grafiği
  - [ ] Sipariş sayısı
  - [ ] Gelir özeti
  - [ ] Dönüşüm oranı
  - [ ] Stok uyarıları (düşük stok)
  - [ ] Ödeme durumu özeti
  - [ ] Son siparişler
  - [ ] Müşteri istatistikleri
- [ ] Dashboard permission-aware (role'a göre farklı widget)

### B. Ürün Yönetimi

- [ ] Ürün listesi (filtreli, aranabilir, sıralanabilir)
- [ ] Ürün oluşturma formu:
  - [ ] Basic Information sekmesi
  - [ ] Pricing sekmesi
  - [ ] Inventory sekmesi
  - [ ] Attributes sekmesi (dinamik — product type'a göre)
  - [ ] Variants sekmesi
  - [ ] SEO sekmesi
  - [ ] Media sekmesi
  - [ ] Shipping sekmesi
  - [ ] Tax sekmesi
  - [ ] Advanced sekmesi
- [ ] Ürün düzenleme
- [ ] Ürün klonlama
- [ ] Bulk operasyonlar (fiyat güncelleme, stok güncelleme, publish)
- [ ] Ürün kalite skoru göstergesi
- [ ] SEO skoru göstergesi
- [ ] Merchant data quality göstergesi

### C. Varyant Yönetimi

- [ ] Varyant listesi (ürün içinde)
- [ ] Varyant oluşturma (kombinasyon oluşturucu)
- [ ] Varyant toplu düzenleme

### D. Kategori Yönetimi

- [ ] Kategori ağacı görünümü (drag & drop sıralama)
- [ ] Kategori oluşturma/düzenleme
- [ ] Kategori SEO yönetimi

### E. Sipariş Yönetimi

- [ ] Sipariş listesi (filtreli: durum, tarih, müşteri)
- [ ] Sipariş detayı
- [ ] Sipariş durum değiştirme
- [ ] Kargo bilgisi girme
- [ ] İade başlatma
- [ ] Sipariş notu ekleme
- [ ] Sipariş durum geçmişi

### F. Ödeme Yönetimi

- [ ] Ödeme listesi
- [ ] Ödeme detayı
- [ ] İade işlemi
- [ ] Banka havalesi onaylama
- [ ] Webhook log görüntüleme

### G. Stok Yönetimi

- [ ] Stok listesi (ürün/depo bazlı)
- [ ] Stok düzeltmesi
- [ ] Depo transferi
- [ ] Stok hareketi geçmişi
- [ ] Düşük stok alarmı listesi

### H. Müşteri Yönetimi

- [ ] Müşteri listesi
- [ ] Müşteri detayı (siparişler, adresler, izinler)
- [ ] Müşteri grubu yönetimi
- [ ] KVKK/veri silme talebi

### I. İçerik Yönetimi

- [ ] Sayfa CRUD
- [ ] Blog yönetimi
- [ ] Menu yönetimi
- [ ] Banner yönetimi
- [ ] Media library

### J. SEO Yönetimi

- [ ] SEO sorunları listesi
- [ ] Redirect yönetimi
- [ ] Robots.txt yönetimi
- [ ] Sitemap görüntüleme

### K. Kampanya & Kupon

- [ ] Kampanya yönetimi
- [ ] Kupon yönetimi
- [ ] Kupon kullanım raporu

### L. Raporlar

- [ ] Satış raporu
- [ ] Sipariş raporu
- [ ] Ürün raporu
- [ ] Müşteri raporu
- [ ] Stok raporu
- [ ] Ödeme raporu

### M. Ayarlar

- [ ] Mağaza ayarları
- [ ] Ödeme yöntemi yapılandırması
- [ ] Kargo sağlayıcı yapılandırması
- [ ] Vergi yapılandırması
- [ ] Email/SMS ayarları
- [ ] Google Merchant Center ayarları
- [ ] Feature flag yönetimi

### N. Audit Log

- [ ] Admin işlemleri log görüntüleme
- [ ] Kim / ne yaptı / hangi kayıt / önceki değer / yeni değer / IP / zaman

### O. Admin Güvenliği

- [ ] MFA zorunlu
- [ ] Session timeout
- [ ] IP bazlı erişim kısıtı (opsiyonel)
- [ ] Login attempt monitoring
- [ ] Audit log entegrasyonu

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Dashboard çalışıyor
- [ ] Ürün yönetimi tam çalışıyor (CRUD + dinamik attribute)
- [ ] Sipariş yönetimi çalışıyor
- [ ] Stok yönetimi çalışıyor
- [ ] Ödeme yönetimi çalışıyor
- [ ] SEO yönetimi çalışıyor
- [ ] RBAC tüm panelde çalışıyor
- [ ] Audit log çalışıyor
- [ ] E2E testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

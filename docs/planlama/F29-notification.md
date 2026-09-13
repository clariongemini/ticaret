# F29 — BİLDİRİM (NOTIFICATION) SİSTEMİ

> **Faz:** 29 — Notification System  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** YÜKSEK  
> **Bağımlılık:** F3 (Auth), F10 (Order), F12 (Invoice), F15 (Localization), F19 (Event Bus)  
> **Yapıdaki Yeri:** Kat 4 sonu — sipariş/ödeme akışı bittikten sonra bildirim katmanı kurulur  
> **İnşaat Analojisi:** Binanın interkom ve alarm sistemi — her olay ilgili kişiye ulaşır

---

## Amaç

Email, SMS, Push, WhatsApp gibi kanalları tek bir merkezi abstraction üzerinden yönetmek. Admin panelden çok dilli şablon oluşturabilmek. Her olay (sipariş, kargo, şifre sıfırlama) doğru kanala doğru dilde ulaşmalı.

---

## ⚠️ Kritik Kurallar

> Her bildirim kanalı plugin gibi eklenebilir — core değişmez.  
> Şablonlar hardcoded değil, admin panelden yönetilir.  
> Çok dilli: aynı event TR kullanıcıya Türkçe, EN kullanıcıya İngilizce gider.  
> Bildirim gönderilemezse: retry → dead letter → admin uyarısı.

---

## Görevler

### A. Notification Channel Abstraction

- [ ] `NotificationChannelInterface` oluşturuldu:
  - `send(Recipient $recipient, NotificationMessage $message): bool`
  - `supports(string $type): bool`
- [ ] Channel implementasyonları (plugin mimarisi):
  - [ ] **Email** — SMTP / Mailgun / SendGrid / SES (configurable)
  - [ ] **SMS** — Netgsm / Twilio / İleti365 (configurable, Türkiye'de Netgsm öncelikli)
  - [ ] **Push Notification** — FCM + APNS (F26'dan entegre)
  - [ ] **WhatsApp Business API** — Meta Cloud API
  - [ ] **Webhook** — Dış sistemlere event push
- [ ] Admin panelden her kanal için: aktif/pasif + API credentials

---

### B. Şablon (Template) Sistemi

- [ ] `bildirim_sablon` tablosu (F2'de tasarlandı, burada implement):
  - `slug` (benzersiz, örn: `siparis-onaylandi`)
  - `kanal` (email / sms / push / whatsapp)
  - `dil` (tr / en / ...)
  - `konu` (email subject)
  - `govde_html` (email body HTML)
  - `govde_metin` (plaintext fallback / SMS / Push body)
  - `degiskenler` (hangi değişkenler kullanılabilir: JSON)
  - `aktif_mi`
- [ ] Şablon değişken sistemi (Blade/Twig benzeri basit):
  - `{{musteri_adi}}`, `{{siparis_no}}`, `{{toplam_tutar}}`, `{{kargo_takip_no}}`
- [ ] Admin panelden şablon düzenleme (WYSIWYG HTML editor)
- [ ] Şablon önizleme (gerçek verilerle test gönderimi)
- [ ] Her şablon için dil bazlı varyant (TR + EN zorunlu)

---

### C. Notification Event'leri (Sipariş Yaşam Döngüsü)

#### Müşteriye giden bildirimler:
- [ ] `siparis-alindi` — Sipariş alındı onayı (email + push)
- [ ] `odeme-basarili` — Ödeme başarıyla alındı (email)
- [ ] `odeme-basarisiz` — Ödeme başarısız (email + push)
- [ ] `siparis-hazirlaniyor` — Sipariş hazırlanıyor (email + push)
- [ ] `siparis-kargolandi` — Kargo takip numarasıyla (email + SMS + push)
- [ ] `siparis-teslim-edildi` — Teslim edildi (email + push)
- [ ] `siparis-iptal-edildi` — İptal bildirimi (email)
- [ ] `iade-onaylandi` — İade onaylandı (email)
- [ ] `iade-tamamlandi` — Para iadesi yapıldı (email)
- [ ] `stok-tukeniyor` — Sepetteki ürün azalıyor (push)

#### Hesap bildirimleri:
- [ ] `hosgeldiniz` — Kayıt sonrası hoşgeldin emaili
- [ ] `email-dogrulama` — Email doğrulama linki
- [ ] `sifre-sifirlama` — Şifre sıfırlama linki
- [ ] `sifre-degisti` — Güvenlik bildirimi (email)
- [ ] `yeni-cihaz-giris` — Yeni cihazdan giriş (email)

#### F27 CX bildirimleri:
- [ ] `stok-geri-geldi` — İzlenen ürün tekrar stokta (email + push)
- [ ] `fiyat-dustu` — İzlenen ürün fiyat düştü (email + push)
- [ ] `yorum-onaylandi` — Yorumunuz yayınlandı (email)
- [ ] `newsletter-hosgeldiniz` — Newsletter aboneliği (email)

#### Admin bildirimleri:
- [ ] `yeni-siparis` — Yeni sipariş geldi (email + dashboard)
- [ ] `dusuk-stok` — Ürün stok eşiği altına düştü (email)
- [ ] `iade-talebi` — Yeni iade talebi (email)
- [ ] `odeme-webhook-hatasi` — Webhook işlenemedi (email + log)

---

### D. Kullanıcı Bildirim Tercihleri

- [ ] `bildirim_tercihleri` tablosu:
  - Her bildirim tipi için: email açık/kapalı, SMS açık/kapalı, push açık/kapalı
- [ ] Zorunlu bildirimler (kapatılamaz): şifre sıfırlama, güvenlik
- [ ] Opsiyonel bildirimler (kullanıcı kapatabilir): stok bildirimi, fiyat alarmı, newsletter
- [ ] Abonelik yönetimi API:
  - `GET /api/v1/store/hesabim/bildirim-tercihlerim`
  - `PUT /api/v1/store/hesabim/bildirim-tercihlerim`
- [ ] Email footer'da tek tıkla abonelik iptali (unsubscribe token)

---

### E. Gönderim Altyapısı

- [ ] Tüm bildirimler **queue** üzerinden gönderilir (request lifecycle dışında)
- [ ] Retry stratejisi: 3 deneme, 5dk / 15dk / 60dk aralıklarla
- [ ] Dead letter: 3 deneme sonrası başarısız → `bildirim_hata_log`
- [ ] Delivery log (`bildirim_log`):
  - `bildirim_tipi`, `kanal`, `alici`, `durum`, `gonderim_zamani`, `hata_mesaji`
- [ ] Admin panelde: Son gönderilen bildirimler, başarısız olanlar

---

### F. WhatsApp Business API Entegrasyonu

- [ ] Meta Cloud API üzerinden WhatsApp Business
- [ ] Onaylı mesaj şablonları (Meta onayı gerekli)
- [ ] Kargo bildirimi şablonu: kargo takip numarası + link
- [ ] Sipariş onayı şablonu
- [ ] Müşterinin WhatsApp numarası alınırsa tercih sistemi

---

### G. Admin Panel

- [ ] Şablon listesi + düzenleme ekranı
- [ ] Test bildirimi gönder (kendi emailine)
- [ ] Son gönderilen bildirimler + durum
- [ ] Başarısız bildirimler + yeniden gönder butonu
- [ ] Kanal konfigürasyonu (SMTP, SMS API, WhatsApp API)

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Email bildirimleri çalışıyor (tüm sipariş lifecycle event'leri)
- [ ] SMS bildirimleri çalışıyor (kargo, ödeme)
- [ ] Push bildirimleri F26 ile entegre çalışıyor
- [ ] Şablon yönetimi admin panelde çalışıyor
- [ ] Çok dilli şablon çalışıyor (TR + EN)
- [ ] Kullanıcı bildirim tercihleri çalışıyor
- [ ] Queue + retry + dead letter çalışıyor
- [ ] Unit ve integration testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

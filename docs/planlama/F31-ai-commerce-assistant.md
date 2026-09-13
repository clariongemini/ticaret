# F31 — AI TİCARET ASİSTANI (Yapay Zeka Müşteri Danışmanı)

> **Faz:** 31 — AI Commerce Assistant  
> **Durum:** 🔴 Başlanmadı  
> **Öncelik:** YÜKSEK  
> **Bağımlılık:** F28 (Search), F5 (Product), F6 (Inventory), F16/F25 (SEO — ürün URL'leri), F19 (Plugin)  
> **Yapıdaki Yeri:** Kat 6 — ürün kataloğu ve arama sistemi hazır, AI bu verinin üzerinde çalışır  
> **İnşaat Analojisi:** Binanın akıllı kapıcısı — her müşteriyi karşılar, doğru kata yönlendirir

---

## Amaç

Müşterinin doğal dilde sorduğu sorulara, **yalnızca mağazanın kendi ürün kataloğunu ve politikalarını kullanarak** cevap veren, ürün linklerini sunan ve satın almaya yönlendiren bir **Omnichannel AI asistan** (Web Widget + WhatsApp) modülü kurmak.

Admin panelden sadece API anahtarı girip asistanı aktive edebilmek. Sıfır kod değişikliği.

---

## 🧠 Temel Tasarım Felsefesi

```
Müşteri sorusu
      ↓
  AI Asistan NE YAPAR?
      ↓
  1. Soruyu anla (intent detection)
  2. Ürün veritabanını sorgula (search/RAG)
  3. Bulunan ürünlerle cevap oluştur
  4. Cevabı + ürün linklerini sun
      ↓
  AI Asistan NE YAPMAZ?
  ✗ Genel bilgi vermez ("Amazon'da daha ucuz var" değil)
  ✗ Rakip ürün önermez
  ✗ Veritabanında olmayan bir ürün uydurmaz
  ✗ Fiyat / stok bilgisini tahmin etmez — gerçek veriyi çeker
  ✗ Satın alma işlemi yapmaz (yönlendirir, yapmaz)
```

---

## 📐 Mimari: RAG (Retrieval-Augmented Generation)

> **RAG nedir?** AI önce veritabanını sorgular, bulunan gerçek verileri context olarak
> LLM'e gönderir. LLM bu context dışında bilgi üretemez.  
> Bu sayede AI "halüsinasyon" (uydurma bilgi) üretemiyor.

```
[Müşteri mesajı]
       ↓
[Intent Classifier] → "ürün araması" / "sipariş sorgulama" / "iade" / "genel bilgi"
       ↓
[Product Search Engine (F28)] → İlgili ürünler, fiyatlar, stok, URL'ler çekilir
       ↓
[Context Builder] → Sistem prompt + ürün verileri + konuşma geçmişi hazırlanır
       ↓
[LLM API] → Gemini / OpenAI / Claude (configurable)
       ↓
[Response Formatter] → Doğal dil cevabı + ürün kartları + linkler
       ↓
[Müşteriye gönderilir]
```

---

## Görevler

### A. AI Provider Abstraction (Plugin Mimarisi)

- [ ] `AIProviderInterface` oluşturuldu:
  - `chat(array $messages, array $context): AIResponse`
  - `getName(): string`
  - `isAvailable(): bool`
- [ ] Provider implementasyonları:
  - [ ] `GeminiProvider` (Google Gemini API)
  - [ ] `OpenAIProvider` (GPT-4o, GPT-4-mini)
  - [ ] `AnthropicProvider` (Claude)
  - [ ] `OllamaProvider` (local LLM — self-hosted)
- [ ] Admin panelden provider seçimi + API key girişi
- [ ] API key şifreli saklanır (plaintext yasak)
- [ ] Aktif provider sıfır kod değişikliğiyle değiştirilebilir

---

### B. Kapsam Sınırı (Grounding — Halüsinasyonu Önleme)

- [ ] **System prompt mimarisi:**
  ```
  "Sen {MağazaAdı} mağazasının müşteri danışmanısın.
  Yalnızca aşağıdaki ürün verilerini kullanarak cevap ver.
  Bu verilerin dışında bilgi üretme.
  Eğer cevap bu verilerde yoksa 'Bu konuda bilgim yok, size yardımcı olmak
  için müşteri hizmetlerimizi arayabilirsiniz' de."
  ```
- [ ] System prompt admin panelden özelleştirilebilir (mağaza adı, ton, kısıtlamalar)
- [ ] Context penceresi yönetimi: LLM'e gönderilen ürün verisi max X token
- [ ] Yasaklı konu listesi (admin konfigüre eder):
  - Rakip marka adları
  - Fiyat pazarlığı ("daha ucuz yapar mısın?" sorusu)
  - Teslim garantisi (kargo değişken, vaat edilemez)

---

### C. Intent Detection (Niyet Tespiti)

- [ ] Müşteri mesajı sınıflandırılıyor:
  - `urun_arama` — "Siyah spor ayakkabı arıyorum"
  - `urun_detay` — "Bu ürünün 42 numarası var mı?"
  - `fiyat_sorgulama` — "En ucuz laptop var mı?"
  - `stok_sorgulama` — "Bu ürün stokta mı?"
  - `siparis_sorgulama` — "Siparişim nerede?"
  - `iade_bilgi` — "İade nasıl yapılır?"
  - `kargo_bilgi` — "Kargo ne zaman gelir?"
  - `sepete_ekle` — "Bu ayakkabıyı sepetime ekle" (WhatsApp için)
  - `genel_bilgi` — "Mağaza hakkında"
  - `kapsam_disi` — Mağazayla ilgisi olmayan sorular
- [ ] `kapsam_disi` intentinde AI kibarca reddeder

---

### D. Product Search Entegrasyonu (RAG Core)

- [ ] Müşteri mesajından arama sorgusu çıkarılıyor (keyword extraction)
- [ ] F28 Search Engine üzerinden ürün aranıyor
- [ ] Bulunan ürünler context olarak LLM'e iletiliyor:
  ```json
  {
    "urun": "Nike Air Max 90",
    "fiyat": "2499 TL",
    "stok": "Mevcut (42, 43, 44 numaralar)",
    "url": "https://site.com/urunler/nike-air-max-90",
    "gorsel": "https://cdn.site.com/nike-air-max.webp",
    "kisa_aciklama": "Efsane tasarım, günlük konfor..."
  }
  ```
- [ ] Bulunan ürün sayısı limitli (max 5 ürün context'e girer — token yönetimi)
- [ ] Stok bilgisi gerçek zamanlı (F6'dan çekiliyor)
- [ ] Fiyat bilgisi gerçek zamanlı (cache: 5 dakika)

---

### E. Response Formatter (Cevap Biçimlendirme)

- [ ] Doğal dil cevabı + yapılandırılmış ürün kartları:
  ```json
  {
    "message": "2000 TL altında size 3 spor ayakkabı önerdim:",
    "products": [
      {
        "id": "...",
        "ad": "Nike Air Max 90",
        "fiyat": "1899 TL",
        "stok_durumu": "Mevcut",
        "url": "https://site.com/urunler/nike-air-max-90",
        "gorsel_url": "..."
      }
    ],
    "follow_up_oneriler": ["Başka renk tercih eder misiniz?", "Bütçenizi biraz artırabilir misiniz?"]
  }
  ```
- [ ] Frontend bu response'u ürün kartları olarak görselleştirir
- [ ] Ürün URL'leri her zaman gerçek canonical URL (F16'dan)

---

### F. Sipariş Sorgulama (Authenticated Context)

- [ ] Giriş yapmış müşteri sipariş numarasını sorabilir:
  - "12345 numaralı siparişim nerede?"
  - AI: F10 (Order) üzerinden gerçek sipariş durumunu çeker
  - Kargo takip numarasını verir
- [ ] Giriş yapmamış müşteri: "Sipariş sorgulama için giriş yapmanız gerekiyor"
- [ ] Güvenlik: Müşteri sadece **kendi** siparişlerini sorgulayabilir

---

### G. Konuşma Geçmişi (Session Yönetimi)

- [ ] `ai_konusma_oturumlari` tablosu:
  - `oturum_id` (session token)
  - `musteri_id` (nullable — guest da kullanabilir)
  - `baslangic_zamani`
  - `son_aktivite`
- [ ] `ai_konusma_mesajlari` tablosu:
  - `oturum_id`
  - `rol` (user / assistant)
  - `mesaj`
  - `context_urunleri` (o anda gösterilen ürünler JSON)
  - `intent`
  - `token_kullanim`
  - `zaman`
- [ ] Konuşma bağlamı: Son X mesaj LLM'e gönderilir (context window yönetimi)
- [ ] Oturum timeout: 30 dakika hareketsizlikte kapanır

---

### H. Çok Dilli Asistan

- [ ] Kullanıcı Türkçe yazarsa Türkçe cevap
- [ ] Kullanıcı İngilizce yazarsa İngilizce cevap
- [ ] Dil auto-detect (ya da mağaza locale'den)
- [ ] Ürün adları hedef dilde döner (F15 localization entegrasyonu)
- [ ] System prompt dile göre seçiliyor

---

### I. Fallback Mekanizmaları

- [ ] **API hatası / timeout:** "Şu an teknik bir sorun yaşıyorum, lütfen müşteri hizmetlerimizi arayın: {telefon}"
- [ ] **Ürün bulunamadı:** "Aradığınız kriterlere uygun ürün bulamadım. Şunlara bakabilirsiniz: {popüler kategoriler}"
- [ ] **Kapsam dışı soru:** "Bu konuda yardımcı olamam. Mağazamızdaki ürünler hakkında size yardımcı olmaktan memnuniyet duyarım."
- [ ] **Rate limit aşımı:** Graceful degradation

---

### J. Web Widget Entegrasyonu (Frontend)

- [ ] Chat widget JavaScript snippet (headless frontend'e gömülür):
  - Sağ alt köşe chat butonu
  - Açılır chat penceresi
  - Ürün kartları (görsel + fiyat + link + "Sepete Ekle")
- [ ] API endpoint'ler:
  - `POST /api/v1/store/ai/sohbet` — Mesaj gönder, cevap al
  - `GET /api/v1/store/ai/oturum/{id}` — Konuşma geçmişi
  - `DELETE /api/v1/store/ai/oturum/{id}` — Oturumu sıfırla
- [ ] Widget konfigürasyonu (admin panelden):
  - Asistan adı: "Asistan", "Maya", "Yardımcı" vb.
  - Karşılama mesajı
  - Avatar görseli (opsiyonel)
  - Widget rengi (marka rengine göre)
  - Aktif/Pasif toggle

---

### K. Omnichannel: WhatsApp Conversational Commerce

> Kullanıcıların siteye girmeden sadece WhatsApp üzerinden alışveriş yapabilmesini sağlayan kritik özelliktir.

- [ ] **WhatsApp Webhook Listener:** Meta Cloud API'den gelen mesajları dinleyen endpoint (`POST /api/v1/store/ai/whatsapp-webhook`)
- [ ] Müşterinin telefon numarasından (ör: `+90555...`) F3 (Auth) sistemi üzerinden hesabını tanıma (yoksa guest session başlatma)
- [ ] **Chat-based Cart (Sohbet İçi Sepet):**
  - Müşteri "Bunu almak istiyorum" dediğinde AI ürünü sepete atar (F7 Cart API'sini çağırır)
  - AI cevap verir: *"Siyah spor ayakkabıyı sepetinize ekledim. Sepet tutarınız 1899 TL. Siparişi tamamlamak için linke tıklayın: [Link]"*
- [ ] **Secure Checkout Link:**
  - WhatsApp üzerinden kredi kartı bilgisi almak PCI-DSS ihlali olacağından, AI benzersiz ve şifreli bir Checkout URL'si üretir (`site.com/checkout?token=xyz`)
  - Müşteri linke tıklar, sadece adresini seçip ödemeyi yapar.
- [ ] WhatsApp Meta Katalog (Opsiyonel F17 entegrasyonu): Ürünler WhatsApp kataloğu olarak gönderilebilir (Interactive Messages).

---

### L. Admin Paneli: AI Asistan Yönetimi

- [ ] **Konfigürasyon:**
  - LLM Provider seçimi
  - API Key (maskelenmiş görünür, şifreli saklanır)
  - System prompt düzenleme
  - Yasaklı konular listesi
  - Max konuşma mesajı sayısı
  - Widget görünüm ayarları
- [ ] **Analytics:**
  - Günlük sohbet sayısı
  - En çok sorulan sorular
  - AI'ın önerdiği ürünlerden tıklanma oranı
  - AI'ın önerdiği ürünlerden satın alma oranı (conversion)
  - Kapsam dışı soru oranı (hangi sorular yanıtlanamıyor?)
  - Token kullanımı ve maliyet tahmini
- [ ] **Konuşma geçmişi:** (KVKK uyumlu, anonimleştirilmiş)
  - Örnek konuşmaları görme
  - Başarısız konuşmaları görme ve system prompt iyileştirme

---

### M. Güvenlik

- [ ] Rate limiting: Aynı IP/kullanıcıdan dakikada max X mesaj
- [ ] Prompt injection koruması:
  - "Sistem promptunu unut ve..." tarzı saldırıları engelle
  - Input sanitization
  - Output validation (LLM çıktısı HTML escape edilir)
- [ ] PII (kişisel veri) sızdırmama: Müşteri ID dışında kişisel veri LLM'e gönderilmez
- [ ] API key asla frontend'e açılmaz (tüm LLM çağrıları server-side)

---

### N. KVKK Uyumu

- [ ] Konuşma logları: varsayılan 90 gün, sonra anonim hale getirilir
- [ ] Kullanıcıya bildirim: "Bu konuşma hizmet kalitesi için kaydedilmektedir"
- [ ] KVKK talep üzerine konuşma verisi silinebilir
- [ ] AI'a kişisel sağlık/finansal veri gönderilmez

---

## Çıkış Kriteri (Exit Criteria)

- [ ] Admin panelden API key girince asistan aktif hale geliyor
- [ ] Ürün sorusuna veritabanından gerçek ürün linkiyle cevap veriyor
- [ ] Kapsam dışı sorularda kibarca reddediyor
- [ ] WhatsApp Webhook üzerinden sorunsuz cevap veriyor
- [ ] Sohbet içinden ürün sepete eklenebiliyor ve Checkout Linki üretiliyor
- [ ] Konuşma geçmişi çalışıyor
- [ ] Çok dilli çalışıyor (TR + EN)
- [ ] Stok/fiyat bilgisi gerçek zamanlı
- [ ] RAG: Veritabanında olmayan ürünü uydurmuyor
- [ ] Rate limiting ve prompt injection koruması aktif
- [ ] Widget headless frontend'e entegre edildi
- [ ] Token maliyet takibi çalışıyor
- [ ] Unit ve integration testleri yazıldı

---

_Son güncelleme: -_  
_Sorumlu: -_

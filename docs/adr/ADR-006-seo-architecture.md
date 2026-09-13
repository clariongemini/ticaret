# ADR 006: Headless SEO Mimarisi

## Status
Kabul Edildi

## Context
Geleneksel e-ticaret sistemlerinde (Monolitik, blade/twig render) SEO (meta etiketleri, schema.org) backend tarafından doğrudan HTML içine basılır. Ancak **Headless** (API-first) mimaride backend sadece JSON döner.
Arama motorları (özellikle Google dışındakiler) Client-Side Rendering (CSR) ile oluşturulmuş React/Vue sayfalarını taramakta büyük sorun yaşar. Bu nedenle frontend ve backend arasındaki SEO sorumluluklarının çok net ayrılması şarttır.

## Decision
Sistem **SSR (Server-Side Rendering) zorunlu Headless SEO** mimarisine sahip olacaktır.

### Karar Detayları:
1. **Frontend Sorumluluğu**: Frontend projesi (Next.js, Nuxt.js vb.) kesinlikle SSR veya SSG yapısında çalışmak zorundadır. `<head>` etiketleri (Title, Meta, Canonical, Hreflang, JSON-LD) sunucuda oluşturulup tarayıcıya (ve arama motoru botlarına) HTML olarak gönderilmelidir.
2. **Backend Sorumluluğu**: Backend'in görevi SEO kurallarını hesaplayıp, tek bir API endpoint üzerinden hazır JSON olarak Frontend'e sunmaktır.
   - Endpoint: `GET /api/v1/store/seo?path=/ayakkabi/nike-air`
   - Bu endpoint, ilgili URL'in hangi entitiy'e (Ürün mü? Kategori mi? CMS sayfası mı?) ait olduğunu çözer ve o sayfaya ait tüm SEO meta datalarını (Title, description, canonical url, hreflang dilleri, Schema.org JSON-LD string'i) döner.
3. **Canonical ve Hreflang Otomasyonu**: Eğer bir ürüne 3 farklı kategori URL'sinden erişilebiliyorsa bile (Örn: `/indirim/nike-air` ve `/ayakkabi/nike-air`), backend her zaman "Asıl" URL'i Canonical olarak Frontend'e iletecektir. Aynı şekilde diğer dil versiyonları `hreflang` olarak hesaplanıp iletilecektir.
4. **Görünmez SEO Yasak**: Veritabanında (Backend'de) hesaplanıp JSON-LD olarak arama motoruna sunulan fiyat, stok, değerlendirme puanı (Rich Snippets); mutlaka sayfada müşteri tarafından gözle görülebilen fiyat ve stokla birebir aynı olmak zorundadır.

## Consequences
- Backend, SEO verilerini yönetmek için spesifik modüllere (F16 ve F25) sahip olacaktır.
- Yeni bir frontend app yazılırken (Örn: React Native mobil app) SEO datası umursanmaz, ancak Web Frontend yazılırken bu API'yi kullanmak zorunludur.

## Related
- F16 — SEO Architecture
- F25 — SEO Automation & Intelligence

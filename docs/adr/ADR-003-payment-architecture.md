# ADR 003: Ödeme (Payment) Mimarisi

## Status
Kabul Edildi

## Context
E-ticaret sistemlerinde ödeme altyapısı, güvenliğin, PCI-DSS uyumluluğunun ve finansal doğruluğun en kritik noktasıdır. Birden fazla ödeme sağlayıcısının (Iyzico, Stripe, PayTR, vb.) aynı sisteme entegre edilebilmesi ve kodun `core` (çekirdek) yapısının bozulmadan bu sağlayıcıların "tak/çıkar" (plugin) mantığıyla çalışabilmesi gerekmektedir. 
Ayrıca mükerrer ödemeleri (duplicate charge) engellemek ve tarayıcı kopmalarında siparişin kaybolmasını önlemek için sağlam bir akışa ihtiyaç vardır.

## Decision
Sistem **Webhook-First (Asenkron Onay)** ve **Plugin Tabanlı** bir ödeme mimarisi kullanacaktır.

### Karar Detayları:
1. **Plugin Interface**: Bir `PaymentGatewayInterface` tanımlanacaktır. Tüm sağlayıcılar bu interface'den türetilir (`charge()`, `refund()`, `verify()`). Core sistem, hangi firmanın kullanıldığını bilmez; sadece interface ile konuşur.
2. **PCI-DSS Scope Minimizasyonu**: Müşterinin kredi kartı bilgileri kesinlikle (loglar dahil) sunucularımızda saklanmayacaktır. Sadece dönen `token` veya `transaction_id` veritabanında tutulacaktır.
3. **Mükerrer Ödeme Koruması (Idempotency)**: Sipariş tamamlanmadan önce veritabanında "Pending" statüsünde bir ödeme kaydı oluşturulur ve ona benzersiz bir `idempotency_key` atanır. Aynı anahtarla bankaya birden fazla istek gitmesi engellenir.
4. **Webhook-First Akış**: Müşteri 3D Secure sayfasından geri dönmese bile, bankadan gelen arkaplan "Webhook" bildirimi siparişi onaylayacak (veya iptal edecek) asıl kaynaktır (Source of Truth). Tarayıcı (Frontend) redirect'lerine güvenilmeyecektir.

## Consequences
- Geliştiriciler yeni bir ödeme sağlayıcı eklerken, sadece `PaymentGatewayInterface`'i uygulayan bir sınıf (Service Provider) yazacaklardır.
- Geliştirme (Local) ortamında test edebilmek için "Fake Payment Gateway" (Tüm charge isteklerine anında HTTP 200 dönen) simülatör zorunlu olacaktır.

## Related
- F9 — Payment Architecture
- F8 — Checkout
- F22 — Security Full Audit

# ADR 007: Plugin (Modül) Mimarisi

## Status
Kabul Edildi

## Context
E-ticaret sistemleri zamanla müşteriye özel (Custom) isteklerle şişer (Örn: "Bizim siparişlerimiz Logo'ya gitsin", "Bizim ürünlerimiz Trendyol'a aktarılsın"). Eğer bu istekler doğrudan `Core` koda (Örn: OrderService içine) yazılırsa, sistem birkaç yıl içinde bakımı imkansız bir "Spaghetti Code" yığınına dönüşür.

Core sistemin %100 temiz kalması ve müşteriye/satıcıya özel kodların sistemden izole edilmesi gerekmektedir.

## Decision
Sistem, tamamen **Event-Driven (Olay Güdümlü) Hook** ve **Interface tabanlı Plugin** mimarisi kullanacaktır.

### Karar Detayları:
1. **Core Değiştirilemez (Immutable Core)**: Hiçbir müşteriye veya 3. parti entegrasyona özel kod (Iyzico, Stripe, Trendyol, Logo) `src/Domain` altındaki core domain sınıflarına yazılamaz.
2. **Event Bus (Olay Veriyolu)**: Core sistem her önemli adımda bir Event fırlatır (Örn: `OrderCreatedEvent`, `ProductPriceUpdatedEvent`).
3. **Plugin Klasör Yapısı**: Harici entegrasyonlar `plugins/` klasöründe (veya özel Composer paketleri olarak) yaşar.
4. **Listener ve Hook'lar**: Bir plugin, dinlemek istediği event'e abone olur (Listener). Örneğin "Logo Entegrasyon Plugini", `OrderCreatedEvent`'i dinler ve sipariş oluştuğunda kendi içinde Logo API'sine istek atar. Çökerse bile (Exception), Core sipariş sürecini etkilemez (Asenkron Queue).
5. **Arayüz (Interface) Zorunluluğu**: Ödeme (Payment), Kargo (Shipping), SMS gibi servisler Interface'lere dayanır. `PaymentGatewayInterface`'i implement eden herhangi bir Plugin, sisteme anında ödeme altyapısı olarak eklenebilir.

## Consequences
- Geliştiriciler yeni bir özellik eklerken Core kodu değiştirmek yerine, bir Plugin yazmaya zorlanırlar.
- Sistemin (Core) güncellenmesi son derece kolaylaşır, çünkü müşteri bazlı kirli kod barındırmaz.
- Performans maliyeti: Event'lerin Queue (Kuyruk) üzerinden asenkron işlenmesi için Redis/RabbitMQ gibi sağlam bir Worker altyapısına ihtiyaç duyulacaktır (F1 ve F23'te ele alınacak).

## Related
- F1 — Core Foundation
- F19 — Plugin System & Event Bus

# ADR 004: Stok Rezervasyon (Inventory Reservation) Mimarisi

## Status
Kabul Edildi

## Context
E-ticarette en sık karşılaşılan sorunlardan biri "Race Condition" (Yarış Durumu) problemidir. İki müşteri aynı anda son 1 adet kalan ürünü satın almaya çalışırsa, veritabanı izolasyon seviyeleri ve uygulama mantığı doğru kurulmamışsa ikisi de ödemeyi yapabilir ve stok -1'e (Negatif stok) düşebilir. 

Bu durumu engellemek için stoğun ne zaman rezerve edileceği (Sepete atıldığında mı, ödeme ekranında mı?) ve teknik olarak nasıl kilitleneceği belirlenmelidir.

## Decision
Stok rezervasyonu, ürün **sepete eklendiğinde değil**, müşteri **ödeme butonuna tıkladığı (Checkout) anda** yapılacaktır.

### Karar Detayları:
1. **Rezervasyon Zamanlaması**: Ürün sepette günlerce durabilir. Stoğu sepetteyken kilitlemek "Dead Stock" (Satılamayan Stok) problemine yol açar. Bu yüzden stok, ödeme işlemi bankaya gönderilmeden hemen önce kilitlenir.
2. **Geçici Rezervasyon Süresi**: Checkout başladığında stok **15 dakika** (yapılandırılabilir) süreyle "Geçici Rezerve" edilir. Bu sürede ödeme bankadan onaylanmazsa, stok serbest kalır. 
3. **Teknik Implementasyon**: 
   - Klasik "Read-then-Write" engellenecektir (`if ($stock > 0) { $stock-- }` YANLIŞTIR).
   - Bunun yerine SQL düzeyinde **Atomic Decrement** kullanılacaktır: `UPDATE inventory SET quantity = quantity - 1 WHERE product_id = X AND quantity >= 1;` 
   - Etkilenen satır sayısı (affected rows) 0 dönerse, stok tükenmiş demektir ve işlem anında iptal edilir.
   - Alternatif/Ek olarak, aşırı yüksek trafikli kampanyalarda **Redis Dağıtık Kilit (Distributed Lock)** mekanizması kullanılacaktır.

## Consequences
- Müşteriler ürünü sepete eklemiş olmalarına rağmen, ödeme ekranında "Stok tükendi" uyarısıyla karşılaşabilirler. Bu, negatif stoğu engellemek için kabul edilmiş bir iş kuralı (business logic) trade-off'udur.
- Veritabanı yapısında `current_stock` ve `reserved_stock` (veya `available_stock`) kavramlarının ayrılması gerekebilir (F6 - Inventory fazında detaylandırılacaktır).

## Related
- F6 — Inventory
- F8 — Checkout
- F9 — Payment Architecture

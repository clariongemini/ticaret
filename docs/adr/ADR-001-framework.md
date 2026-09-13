# ADR 001: Framework Seçimi

## Status
Kabul Edildi

## Context
Projeyi sıfırdan "API-First", "Headless" ve "Modular Monolith" mimariyle, PHP + MySQL kullanarak geliştireceğiz. Geliştirme hızı, uzun vadeli bakım, güvenlik ve e-ticaret için kritik olan kuyruk (queue), olay (event), önbellekleme (cache) ve test altyapısına güçlü ve native destek veren bir framework seçilmesi gerekmektedir. 

Temel adaylar:
1. **Laravel**: Yüksek geliştirici verimliliği, geniş ekosistem, native queue/event/cache desteği.
2. **Symfony**: Yüksek mimari esneklik, kurumsal standartlar, katı nesne yönelimli tasarım.

## Decision
**Laravel** kullanılmasına karar verilmiştir. 

### Karar Gerekçeleri:
1. **Ecosystem & Native Özellikler**: E-ticaret platformları ağır asenkron işlemlere dayanır (stok güncelleme, e-posta gönderimi, webhooklar). Laravel'in yerleşik Queue, Event Bus, Broadcasting, Cache ve Scheduler altyapısı bu ihtiyaçları üçüncü parti paketlere (veya custom kodlara) ihtiyaç duymadan karşılar.
2. **API-First & Headless Uyumu**: Laravel, API kaynakları (Eloquent Resources), API rate limiting ve Sanctum/Passport ile güçlü bir API geliştirme deneyimi sunar.
3. **Test Altyapısı**: F21'de planlanan test (Unit, Feature, Fake providers) ve Database Seeding/Factory yapısı Laravel'de standart ve son derece gelişmiştir. Bu, "test coverage" kuralını uygulamamızı kolaylaştıracaktır.
4. **Modüler Monolit**: Laravel, `nWidart/laravel-modules` veya özel klasör yapılarıyla modüler mimariye (Domain-Driven Design - DDD) kolayca adapte edilebilir.
5. **Topluluk ve Uzun Vadeli Bakım**: En geniş PHP topluluğuna sahip olması, gelecekte geliştirici bulmayı ve sorun çözmeyi hızlandıracaktır.

## Consequences
- **Trade-off**: Symfony'nin sunduğu katı mimari kurallar yerine Laravel'in sunduğu esneklik (facades, magic methods) "developer discipline" gerektirecektir. Bu riski hafifletmek için statik analiz araçları (PHPStan) ve katı linting kuralları uygulanacaktır.
- **Mimari Disiplin**: Laravel'in varsayılan `app/` dizin yapısı yerine, DDD (Domain-Driven Design) prensiplerine uygun, domain bazlı (`src/Domain`, `src/Application`, vb.) özel bir dizin yapısı kurulacaktır. (Bu konu F1'de detaylandırılacaktır).

## Related
- F1 — Core Foundation
- F18 — API Standartları

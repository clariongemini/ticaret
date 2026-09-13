# 🚀 Antigravity E-Commerce Core Platform (Enterprise Edition)

**Sistem Mimarı (System Architect):** Ulaş Kaşıkcı  
**Durum:** Mimari Tasarım & Planlama Aşaması (F0)  
**Teknoloji Yığını:** PHP, MySQL 8+, Redis, Elasticsearch / Meilisearch

---

## 📖 Genel Bakış (Overview)

Antigravity E-Commerce, geleneksel e-ticaret sitelerinin ötesinde; **Pazar Yeri veya SaaS modeline evrilebilecek**, tamamen "Headless" ve "API-First" prensipleriyle tasarlanmış kurumsal seviyede bir ticaret çekirdeğidir (Commerce Core). 

Monolitik spagetti kodlardan kaçınarak, dünyanın en büyük platformlarının (Shopify Plus, Commercetools) modern mimari prensiplerini barındırır. Web, Mobil, B2B portalları ve hatta AI Ajanları bu merkezi yapıya API'ler üzerinden bağlanarak işlem yapar.

## 🏗️ Temel Mimari Prensipler (Architecture Principles)

Bu projenin inşası, katı mühendislik kurallarına ([ARCHITECTURE_MASTER_SPEC.md](ARCHITECTURE_MASTER_SPEC.md)) tabidir:

*   **Strict Modular Monolith:** Modüller (Katalog, Sipariş, Ödeme) birbirinden tamamen izoledir. Veritabanı tabloları modüllere aittir ve çapraz SQL sorguları yasaktır.
*   **Headless & API-First:** Frontend bağımsızdır (Agnostic). Sistem, OpenAPI 3.1 standardında %100 kapsayıcı API'ler sunar.
*   **Pragmatic Event-Driven:** İş mantığı açısından kritik olan durum değişiklikleri (Örn: Sipariş Alındı, Stok Rezerve Edildi) Domain Event'ler üretir.
*   **Multi-Tenant İzolasyonu:** Tek bir veritabanı kurulumu üzerinden sınırsız sayıda marka, mağaza (Store) ve satış kanalı (Channel) tamamen izole bir şekilde yönetilir.

## 💎 Öne Çıkan Özellikler (Key Capabilities)

*   **Dinamik Özellik Motoru (Attribute Engine):** Giyimden elektroniğe her ürün tipine özel özellikleri relational+JSON hibrit model ile esnek ve yüksek performanslı şekilde yönetir.
*   **Canonical Stok & Rezervasyon (Inventory & Ledger):** Stok yarışlarını (Race Condition) önleyen katı rezervasyon durum makineleri ve değişmez (Immutable) hesap defteri mantığı.
*   **Idempotency & Webhook Güvenliği:** Mükerrer siparişleri ve ödemeleri engelleyen insert-first deduplication ve idempotency korumaları.
*   **B2B Corporate Modülü:** Şirketlere özel kredi (Açık Hesap) limitleri, çok katmanlı onay mekanizmaları ve hiyerarşik fiyatlandırma motoru (Tier Pricing).
*   **AI Commerce API:** RAG (Retrieval-Augmented Generation) tabanlı yapay zeka asistanları için tasarlanmış, güvenlik seviyelendirmesine (Low, Medium, High Risk) sahip özel araç (Function Calling) katmanı.
*   **Gelişmiş Kural Motoru (Rule Engine):** Sepet, müşteri ve sipariş bağlamına göre çalışan, soyut sözdizimi ağacı (AST) ile modellenen dinamik promosyon ve indirim altyapısı.

## 📚 Dokümantasyon & Proje Yönetimi

Sistemin inşası, anayasal bir hiyerarşiyle yürütülür. Lütfen geliştirme yapmadan önce aşağıdaki belgeleri inceleyin:

*   📜 [**YAPILACAKLAR.md (Master Checklist):**](yapilacaklar.md) 10 Katmanlı (36 Faz) proje yürütme planı ve durum tablosu.
*   🏛️ [**ARCHITECTURE_MASTER_SPEC.md:**](ARCHITECTURE_MASTER_SPEC.md) Sistemin mühendislik anayasası, kesin kontratlar ve invaryantlar (Invariants).
*   🎯 [**PROJE_DETAY.md:**](PROJE_DETAY.md) Projenin genel vizyonu ve yönetici özeti.
*   📂 [**/docs/planlama:**](docs/planlama/) Her faz (F0-F34) için detaylandırılmış adım adım mimari analiz ve geliştirme görevleri.
*   📂 [**/docs/adr:**](docs/adr/) Architecture Decision Records (Mimari Karar Kayıtları).

---
> _"Mükemmel bir sistem, eklenecek bir şey kalmadığında değil; çıkarılacak bir şey kalmadığında ortaya çıkar."_ — Antoine de Saint-Exupéry

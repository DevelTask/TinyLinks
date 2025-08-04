[Конфиги]
- web.php - веб-настройки (роуты, redis, rate limit)
- console.php - консольные команды (очереди)
- params.php - базовые параметры (url, лимиты)
- db.php - подключение к БД (основное и read-only)
- container.php - DI-контейнер (сервисы)

[Классы]
- SiteController - редирект + логирование визитов
- LinkController (API) - создание коротких ссылок

[Сервисы]
- Генерация ссылок (LinkService)
- Кэширование (LinkCacheService)
- Детектор ботов (RemoteBotDetector)
- Генератор кодов (RandomShortCodeGenerator)
- Пул кодов (ShortCodePoolProvider)
- Обработчик лимитов (RateLimitResponder)
- Просто прямой запрос LinkAnalyticsService (с учетом партиционирования)

[Rate Limit]
- ThrottleFilter - ограничение запросов
- RequestInfoProvider - идентификация (IP+UA)
- GlobalThrottleBehavior - глобальное применение

[DTO/Модели]
- RateLimitConfig - настройки лимитов
- CreateShortLinkRequest - валидация URL
- Link/LinkVisit - модели БД (ссылки+визиты)

[Другое]
- Репозитории (LinkRepository, LinkVisitRepository)
- Миграции (links, visits, queue таблицы)
- Очереди (LogVisitJob - фоновое логирование)
- Тесты (фильтры, кэш, генерация кодов)
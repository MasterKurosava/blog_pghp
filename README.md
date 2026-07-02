# IT-блог

Небольшой блог на чистом PHP без фреймворка. Статьи сгруппированы по категориям, есть поиск, сортировка и бесконечная подгрузка материалов.

## Стек

- **Backend:** PHP 8.2, PDO, MySQL 8
- **Шаблоны:** Smarty 4
- **Frontend:** SCSS, vanilla JS
- **Инфраструктура:** Docker (nginx, php-fpm, MySQL, phpMyAdmin)

## Архитектура

Запрос проходит по цепочке:

```
HTTP → Kernel → Middleware → Router → Controller → Service → Repository → PDO
```

- **Controller** — принимает запрос, отдаёт HTML или JSON
- **Service** — бизнес-логика, подготовка данных для шаблонов
- **Repository** — SQL-запросы и маппинг в модели
- **View** — рендер Smarty-шаблонов

Зависимости собираются через простой DI-контейнер (`AppServiceProvider`).

## Структура проекта

```
app/           Контроллеры, сервисы, репозитории, модели
config/        Маршруты, настройки, тексты интерфейса, контент для сидов
templates/     Smarty-шаблоны (страницы, компоненты, partials)
public/        Точка входа, CSS, JS, статика
database/      SQL-миграции
bin/           migrate.php, seed.php
docker/        Dockerfile и конфиг nginx
```

## Быстрый старт

1. Скопировать переменные окружения:

```bash
cp .env.example .env
```

2. Поднять контейнеры:

```bash
docker compose up -d
```

3. Установить зависимости и подготовить БД:

```bash
docker compose exec php composer install
docker compose exec php php bin/migrate.php
docker compose exec php php bin/seed.php
```

4. Собрать стили:

```bash
npm install
npm run build:css
```

## Страницы и API

| URL | Описание |
|-----|----------|
| `/` | Главная — блоки категорий с последними статьями |
| `/categories` | Список категорий |
| `/category/{slug}` | Статьи категории, сортировка, infinite scroll |
| `/article/{slug}` | Страница статьи |
| `/api/search?q=` | Поиск статей по заголовку (JSON) |
| `/api/category/{slug}/articles` | Подгрузка статей категории (JSON, для infinite scroll) |

## Основные возможности

**Категории и статьи** — slug-URL, хлебные крошки, связь many-to-many через `article_category`.

**Сортировка на странице категории** — по дате (новые / старые) и по просмотрам. Настройки в `config/category.php`.

**Infinite scroll** — вместо пагинации статьи подгружаются порциями при прокрутке. Первая порция рендерится на сервере, остальные — через API.

**Поиск** — живой поиск в шапке с debounce, результаты без перезагрузки страницы.

**UI** — design system на SCSS-токенах, скелетоны, анимации, модалка «Поделиться», toast-уведомления, кнопка «Наверх».

**Контент** — демо-данные на русском из `config/content.php` и `config/categories.php`, без Lorem Ipsum.

## Конфигурация

| Файл | Назначение |
|------|------------|
| `.env` | Окружение, БД, debug-режим |
| `config/routes.php` | Маршруты |
| `config/category.php` | Статей на страницу, варианты сортировки |
| `config/strings.php` | Тексты интерфейса (`str('key')`) |
| `config/content.php` | Заголовки и тексты статей для сидов |
| `config/categories.php` | Категории для сидов |

## Разработка

Пересборка CSS при изменениях:

```bash
npm run watch:css
```

Пересоздать демо-данные:

```bash
docker compose exec php php bin/seed.php
```

Применить новые миграции:

```bash
docker compose exec php php bin/migrate.php
```

## Требования

- Docker и Docker Compose
- Node.js и npm (только для сборки SCSS)

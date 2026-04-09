# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

### Setup & Development

```bash
composer setup     # Full setup: install deps, create .env, migrate, build frontend
composer dev       # Run all dev processes concurrently: Laravel server + queue + logs + Vite
```

### Backend

```bash
composer lint              # Format PHP files with Pint
composer lint:check        # Check PHP style without changes
composer test              # Clear config, lint check, run PHPUnit tests

# After modifying PHP files, always run:
vendor/bin/pint --dirty --format agent
```

### Frontend

```bash
npm run dev           # Vite dev server
npm run build         # Production build
npm run types:check   # TypeScript type checking
npm run lint          # ESLint with auto-fix
npm run format        # Prettier formatting
```

After modifying frontend files, run: `npm run types:check && npm run lint && npm run format`

### Artisan

```bash
php artisan migrate          # Run migrations
php artisan queue:listen     # Process queue jobs
php artisan pail             # Stream logs
```

## Architecture

### Stack

- **Backend:** Laravel 13 (PHP 8.3+), Eloquent ORM, Sanctum (auth), Horizon (queue UI)
- **Frontend:** Vue 3.5 (Composition API + TypeScript), Vite, TailwindCSS 4, PrimeVue 4, TanStack Vue Query 5
- **Database:** SQLite (dev) / PostgreSQL (prod), Redis (cache/queue)
- **AI:** Laravel AI package abstracting Anthropic, OpenAI, Gemini, Groq, DeepSeek, Mistral

### Request Flow

```
Frontend (Axios @ resources/js/api/) → /routes/api.php → Controllers → Command/Handler → Models → DB
```

### Backend Structure

```
app/
├── Http/                        # Controllers, Resources (Laravel standard)
├── Console/                     # Artisan commands (Laravel standard)
├── Providers/                   # Service providers (Laravel standard)
├── Ai/                          # AI agents (app/Ai/Agents/)
└── Modules/                     # Business logic grouped by domain
    ├── Page/                    # Handlers, Models
    ├── Plan/                    # DTO, Handlers
    ├── Prototype/               # Commands, Enums, Handlers, Models, Support
    └── User/                    # Models
```

This is **not DDD** — it's a pragmatic module grouping. No strict layer rules, just logical separation.

### Backend Conventions

Write operations use the **Command/Handler pattern**:
- Commands are simple data carriers (e.g., `CreatePrototypeCommand`)
- Handlers contain application logic (e.g., `CreatePrototypeHandler`)
- Handlers expose `__invoke(...)` and are called as invokable objects
- Async work goes in Jobs (`app/Jobs/`)

Read operations use controllers directly with Eloquent. API responses use Eloquent API Resources.

Models live inside their respective module: `app/Modules/{Module}/Models/`.

### Frontend Conventions

- Data fetching and mutations: TanStack Vue Query (see `resources/js/app/plugins/vue-query.plugin.ts`)
- API calls: modules in `resources/js/api/` using the shared Axios client (`http.client.ts`)
- Shared logic: composables in `resources/js/composables/`
- Types: `resources/js/types/`
- Path alias: `@` → `resources/js/`

### AI Agents

AI agents live in `app/Ai/Agents/` and implement Laravel AI contracts (`Agent`, `Conversational`, `HasTools`). Use the `Promptable` trait for system instructions.

### Key Config

- `config/ai.php` — AI provider settings and keys
- `config/project.php` — Prototype base path/URL and templates path
- `PROTOTYPES_BASE_PATH/URL` and `TEMPLATES_BASE_PATH/URL` env vars control where generated files are stored and served

## Core Principles (from AGENTS.md)

- Prefer working solutions over perfect architecture; favor speed of iteration
- Keep everything simple — avoid unnecessary abstractions
- Use Eloquent models as the primary way to work with domain data
- DTOs, Value Objects, Entities, and Command/Handler are justified only when complexity warrants it
- Tests are not required in this project
- Reuse existing patterns and logic rather than introducing new ones

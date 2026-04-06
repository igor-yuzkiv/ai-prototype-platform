# Core Principles

- Prefer working solutions over perfect architecture
- Keep everything simple and understandable
- Avoid unnecessary abstractions
- Do not introduce complex patterns by default
- Reuse existing logic instead of rewriting
- Keep the system easy to debug and inspect
- Favor speed of iteration over purity
- Tests are not required in this project

---

# Backend Stack

**Primary:** Laravel with Eloquent models as the main way to work with domain data.

Additional abstractions may be used only when justified:

- DTO
- Value Object
- Entity
- Command / Handler

For APIs, default to Eloquent API Resources with versioning — unless existing routes don't follow this convention, in which case follow the existing pattern.

## Command / Handler Approach

This project follows a lightweight Command / Handler style.

- Write operations should preferably use Command + Handler
- Read operations may use Queries only when needed
- Do not introduce full CQRS complexity unless the task clearly requires it
- Avoid ceremony — keep handlers focused and practical
- Commands should be simple data carriers
- Handlers should contain the actual application logic

## Code Formatting

After modifying any PHP files, run the formatter before finalizing changes:
```bash
vendor/bin/pint --dirty --format agent
```

> Do not run `--test` mode. Always run with `--format agent` to apply fixes directly.

---

# Frontend Stack

**Libraries:** Vue · TypeScript · PrimeVue · TailwindCSS · ESLint · Prettier

## Structure

Keep the frontend structure simple and flat. Follow the structure already used in the project. Do not introduce complex frontend architecture unless explicitly required.

Typical folders:

- `app`
- `components`
- `composables`
- `types`
- `pages`
- `utils`
- `libs`

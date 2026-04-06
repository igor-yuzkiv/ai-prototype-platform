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

## Conventions

- Use vue-query for all data fetching and mutations:
    - Query keys are stored in `config/` — one file per entity (e.g. `config/projects.keys.ts`)
    - Queries and mutations are extracted into dedicated composables in their respective folders
      (e.g. `composables/useProjectsList.query.ts`, `composables/useCreateProject.mutation.ts`)
- Extract shared logic into composables
- Prefer framework or library components over custom implementations where possible

## Structure

- Keep the frontend structure simple and flat. Follow the structure already used in the project. 
- Do not introduce complex frontend architecture unless explicitly required.
- Components are organized into subfolders by feature or entity — do not place all files directly in `components/`.
- Use barrel `index.ts` files to export from each subfolder.
- 
Typical folders:

- `app`
- `components`
- `composables`
- `types`
- `pages`
- `query`
- `mutation`
- `utils`
- `libs`
- `config`

## File Naming

- Vue components — PascalCase: `ProjectCard.vue`, `CreateProjectModal.vue`
- Composables, services, and other TypeScript files — kebab-case with dot-separated type suffix:
    - `use.projects-list.query.ts`
    - `use.create-project.mutation.ts`
    - `projects.service.ts`
    - `projects.keys.ts`

## Code Quality

After modifying any frontend files, run the following checks before finalizing changes:
```bash
npm run types:check
npm run lint
npm run format
```

> Adjust script names to match those defined in `package.json` if they differ.

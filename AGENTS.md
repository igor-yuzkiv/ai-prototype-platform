## Project Context

This project is an AI-powered prototype generator.

The goal is to let a user:
- create a project
- describe requirements (markdown)
- generate a working prototype (UI + basic logic)
- edit and iterate on the result

The system uses LLM agents to:
- analyze requirements
- plan structure (files, pages, logic)
- generate code
- update existing code based on user feedback

This is a **demo / MVP-oriented system**.

Keep everything simple, practical, and working.

Do not overengineer.

---

## Core Principles

- Prefer working solutions over perfect architecture
- Keep everything simple and understandable
- Avoid unnecessary abstractions
- Do not introduce complex patterns by default
- Reuse existing logic instead of rewriting
- Keep the system easy to debug and inspect
- Favor speed of iteration over purity

---

## Main Flow (Important)

The system works as a pipeline:

1. User creates project
2. User writes requirements (markdown)
3. LLM processes requirements
4. LLM generates:
    - file structure
    - description of files
    - tasks for implementation
5. Tasks are executed by agents
6. Code is generated and written to files
7. User can:
    - view files
    - edit files manually
    - request changes via LLM

Agents MUST respect this flow.

Do not skip steps.
Do not merge planning and implementation unless explicitly required.

---

## Backend Stack

- Laravel
- Eloquent models
- PostgreSQL

### Backend Rules

- Use Laravel as intended
- Prefer Eloquent over custom abstractions
- Do not introduce repository pattern by default
- Keep controllers thin
- Put logic in:
    - Services
    - Handlers
    - Models (only if simple)

### Structure (Preferred)

- `app/Models`
- `app/Services`
- `app/Handlers`
- `app/Http`
- `app/Console`
- `app/Libs`

Do not create deep module structures.


---

## Agent Rules

- Do not redesign the project structure
- Do not invent new architecture unless required
- Follow planning output strictly
- Keep generated code simple and readable
- Avoid overengineering
- Avoid “smart” abstractions

If unsure — choose the simplest working approach.

---

## Frontend Stack

- Vue
- TypeScript
- TailwindCSS
- PrimeVue

### Frontend Rules

- Keep components simple
- Use Composition API
- Avoid large components
- Put logic into composables when needed
- Keep UI clean and minimal

Avoid:
- complex state management
- unnecessary libraries
- heavy abstractions

---

## Code Style

- Prefer explicit over clever
- Keep functions small
- Use clear naming
- Follow existing patterns in project

Bad:
- overly generic helpers
- deep inheritance
- hidden logic

Good:
- simple functions
- predictable structure
- readable code

---

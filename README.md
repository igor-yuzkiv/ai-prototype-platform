# AI Prototype Platform

A web application that turns natural language requirements into interactive HTML/CSS prototypes using AI. Describe what you want to build, and the platform generates a structured plan and implements each page automatically.

## Features

- **AI-powered planning** — analyzes requirements and produces a structured prototype plan with pages and user flows
- **Automatic page generation** — each page is implemented as a self-contained HTML prototype
- **Multi-provider AI** — supports Anthropic, OpenAI, Gemini, Mistral, Groq, and more via [Laravel AI](https://github.com/patrickgunnar/laravel-ai)
- **Real-time queue processing** — generation jobs run asynchronously with Laravel Horizon
- **Monaco Editor** — in-browser code editor for viewing and editing generated pages

## Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 13, PHP 8.3+, Sanctum, Horizon |
| Frontend | Vue 3.5, TypeScript, Vite, TailwindCSS 4, PrimeVue 4 |
| Data fetching | TanStack Vue Query 5 |
| Database | PostgreSQL (prod) |
| Cache / Queue | Redis |

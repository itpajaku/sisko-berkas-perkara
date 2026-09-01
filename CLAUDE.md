# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

"Sistem Kontrol Berkas Perkara" — a court case-file management system for a religious court registry (kepaniteraan). It tracks case files (berkas) — gugatan, permohonan, akta cerai, PBT — their distribution/expedition status (ekspedisi), case data synced from the external SIPP system, reports, user/menu access control, and an ATK stock-opname module.

The codebase, UI text, and commit messages are in **Indonesian**. Follow that convention for user-facing strings and messages.

## Setup & Common Commands

No test suite, linting, or build step exists for the application code (assets in `public/assets` are prebuilt). The webroot is `public/`.

- Install dependencies: `composer install`
- Copy `.env.example` to `.env` and fill in DB + SIPP credentials
- Run migrations: `vendor/bin/phinx migrate`
- Rollback last migration: `vendor/bin/phinx rollback`
- Run all seeds: `vendor/bin/phinx seed:run`
- Create a new migration: `vendor/bin/phinx create <MigrationName>` (writes to `db/migrations`)
- Create a new seeder: `vendor/bin/phinx seed:create <SeederName>` (writes to `db/seeds`)

Phinx config (`phinx.php`) reads DB credentials from `.env` for the `development` environment.

## Stack

- **CodeIgniter 3** (legacy framework) as the request/routing/loader layer
- **Eloquent ORM** (`illuminate/database`) for models via a Capsule manager
- PHP >= 8.2; composer PSR-4 namespaces: `App\Libraries\`, `App\Services\`, `App\Models\`, `App\Traits\` mapped under `application/`
- **Phinx** for migrations/seeds
- **phpdotenv** loads `.env` into `$_SERVER` (access via `$_ENV[...]`)
- **Hashids** for obfuscating record IDs in URLs
- **PhpWord** (`TemplateProcessor`) to generate `.docx` reports from templates in `doc/template/` (output to `doc/output/`)
- **HTMX** frontend: pages are plain HTML; dynamic UI is server-rendered HTML fragments swapped in via HTMX. CSRF is auto-injected into HTMX and jQuery requests on non-GET verbs.

## Architecture (request flow)

`public/index.php` boots dotenv + CI. Requests go: `application/config/routes.php` → controller → service → Eloquent model → view (via `Templ`).

- **Base controllers** (`application/core/MY_Controller.php`):
  - `MY_Controller` swaps the loader for `azharlihan/ci3-modules\BaseLoader`.
  - `APP_Controller` (extend this for feature controllers) boots Eloquent, sets up `Sysconf`, sets Carbon locale to `id`, and enforces auth via `AuthData::authenticatedPass()`.
- **Routes** (`application/config/routes.php`): CI3-style routes, including HTTP-method-specific routes (e.g. `$route['berkas_permohonan/(:any)']['PATCH']`). `translate_uri_dashes = true`, so URL dashes map to underscores.
- **Controllers** enforce HTTP verbs with `App\Libraries\MethodFilter::must('post'|'patch'|'delete'|'get')`, read input via `App\Libraries\RequestBody`, run validation traits (`App\Traits\*Validation` using CI's form_validation), and delegate business logic to `App\Services\*Service`. Exceptions are caught and rendered as `components/exception_alert` fragments.
- **Models** are Eloquent models in `App\Models` extending `Illuminate\Database\Eloquent\Model`, with `protected $connection = "default"`. The `booted()` hook auto-fills `hash_id` on create (using `App\Libraries\Hashid`). URLs expose `hash_id`, not raw IDs — controllers decode with `Hashid::singleDecode()`.
- **Views** are rendered via `App\Libraries\Templ`: `Templ::render("view")->layout("layouts/main_layout", [...])` for full pages, and `Templ::component("some/fragment", $data)` for reusable HTML fragments (DataTables columns, alert boxes, cards, chart partials). Fragments live under `application/views/components/` and per-feature `components/` folders.

### Eloquent / database

- `App\Libraries\Eloquent::boot()` registers **two** connections: `sipp` (from `DB_*_SIPP` env vars, read-only external court DB) and `default` (the app DB). Use `Eloquent::get_instance()` or the query builder for transactions (`->beginTransaction()` / `commit()` / `rollback()`).
- Case data tables (perkara, putusan, penetapan, pihak, etc.) are read from the SIPP connection; the app's own tables (berkas_*, berkas_ekspedisi, posisi_ekspedisi, menus, users, atk_*) live on `default`.
- Migration columns use `snake_case` and timestamps via `$table->addTimestamps()`.

### Key domain concepts

- **Berkas** (case files): `berkas_gugatan`, `berkas_permohonan`, `berkas_akta`, and PBT. Each tracks a `perkara_id`, party data, judges (majelis), dates (pendaftaran/putusan/arsip), and an **ekspedisi** trail — a polymorphic many-to-many (`berkas_ekspedisi`) attaching the file to `posisi_ekspedisi` positions (save points) as it is distributed.
- **SIPP sync**: `SinkronController` / `db/transfers` pull case data from SIPP into the app and can push back (e.g. BHT). Progress/logs stream over SSE.
- **Access control**: dynamic menus stored in DB (`menus`, `menu_sections`, `access_menu`, `allowed_group`, `access_menu_section`) managed via `MenuService` and `PengaturanController`.
- **Reports**: `generate_doc()` in services fills PhpWord `.docx` templates (see `BerkasPermohonanService`, `BerkasGugatanService`, `AktaCeraiService`) and force-downloads from `doc/output/`.

## Conventions

- Model files define Eloquent relations (e.g. `berkas->ekspedisi()` morphToMany, `->perkara()` belongsTo). Keep business rules in services, validation in traits, HTTP/render logic in controllers.
- When adding DB-backed tables, create both a Phinx migration and seed in `db/`.
- Report `.docx` templates live in `doc/template/`; match template placeholders exactly (e.g. `setValue("NIP_PEJABAT", ...)`).
- `.env` holds `APP_SALT` (used by Hashid) and `APP_KEY`; never commit real credentials.

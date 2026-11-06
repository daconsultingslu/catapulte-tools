# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Catapulte-Tools** is a Symfony 7.2 web application for managing interactive training events. It provides a suite of participant engagement tools (signature, QCM, survey, word cloud, self-evaluation, tetris, trial) deployed via FrankenPHP + Caddy.

## Commands

### Docker (recommended)
```bash
docker compose build --no-cache
docker compose up --pull always -d --wait
docker compose down --remove-orphans
```

### Symfony Console
```bash
bin/console cache:clear
bin/console doctrine:migrations:migrate
bin/console doctrine:migrations:diff        # Generate migration from entity changes
bin/console app:create-user                 # Create user accounts
bin/console app:purge-event                 # Clean up old events
```

### Local Development
```bash
composer install
symfony server:start
```

## Deployment

Two separate Upsun projects are connected to this GitHub repository. Each one receives every branch, but only its production branch is deployed:

| Upsun project | ID | Production branch |
|---|---|---|
| Tools - Catapulte | `glqe6ievjazws` | `main` |
| Tools - Stellantis | `5vzr3ch63fuc2` | `stellantis` |

- Deploy by updating the production branch on `origin` (GitHub). The GitHub integration forwards it to Upsun, so do not push to the `platform` or `upsun` remotes.
- `main` only accepts changes through a pull request.
- `main` and `stellantis` intentionally have different features. Never merge one into the other: apply shared changes (such as a runtime upgrade) to each branch separately.

## Architecture

### Core Domain
- **Event** → **Session** → **Tool instances** (one type per session)
- **Brand** + **Theme** for visual customization per organization
- **User** participates in sessions, tracked via `{Tool}UserData` entities
- **GroupEvent** assigns users to events

### Tool System
Each interactive tool follows a strict pattern:
- `src/Entity/Tools/{Name}/` — entities: `{Name}Tool`, `{Name}UserData`, plus tool-specific sub-entities
- `src/Controller/Tools/{Name}ToolController.php` — display/interaction endpoints
- `src/Controller/Admin/{Name}ToolCrudController.php` — EasyAdmin CRUD
- `src/Form/{Name}Type.php` — form types
- `templates/tools/{name}/` — Twig templates

**Tool inheritance:** All tool entities extend `Tool` (abstract, Single Table Inheritance in `src/Entity/Tools/`).

**Available tools:** Signature, QCM, Survey, WordCloud, SelfEvaluation, Tetris, Trial.

### Security
- Role hierarchy: `ROLE_SUPER_ADMIN > ROLE_ADMIN > ROLE_USER`
- `/admin/*` requires `ROLE_ADMIN`; everything else requires `ROLE_USER`
- `/login` and `/rest/trial/new` are public
- Two authenticators: `UserAuthenticator` (credentials) and `UrlTokenAuthenticator` (token in URL)

### Key Services
- `QrcodeService` — generates QR codes with tokens (used by Signature tool)
- `DataUrlToImageService` — converts canvas base64 to image files (signature capture)
- `ImportExportService` — bulk user import/export via spreadsheet (phpspreadsheet)
- `GraphBuilderService` — builds chart data for tool results

### Database
MySQL/MariaDB 8.x via Doctrine ORM 3.x. Migrations in `migrations/`. The `stof/doctrine-extensions-bundle` handles timestamps automatically.

### Frontend
Asset Mapper (no Webpack). JavaScript dependencies managed via ImportMap. Twig templates use FontAwesome icons and jQuery for DOM interactions. Symfony UX TwigComponent for reusable UI components.

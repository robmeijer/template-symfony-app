# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Architecture

Docker-first Symfony 8 app on PHP 8.5 / FrankenPHP. Key layout:

- `app/` — Symfony 8.0 application (all PHP, Twig, assets)
- `docker/` — `Dockerfile` (FrankenPHP base, multi-stage `app` → `local`) + `docker-entrypoint.sh`
- `compose.yaml` + `compose.override.yaml` — Docker Compose; the override adds the PostgreSQL 18 database, mounts
  `./app` as a volume, and runs a Sass watcher (`styles` service)

The Dockerfile uses multi-stage builds: `app` (prod) → `local` (dev, extends `app`). The local stage volume-mounts
`./app`, so edits to PHP/Twig/assets are live without rebuilding.

Stack: PHP 8.5, Symfony 8.0, Doctrine ORM 3, PostgreSQL 18, Symfony AssetMapper, symfonycasts/sass-bundle.

On container start (`docker-entrypoint.sh`): dev env runs `composer install` + `doctrine:migrations:migrate`; prod env
builds sass assets and compiles asset-map.

Sass is compiled via a separate `styles` container running `bin/console sass:build --watch`.

Deployment target: Fly.io (`fly.toml`).

## Local Development

```shell
# Start all services (app + database + sass watcher)
docker compose up --build --wait
# App is available at https://localhost
```

Enable Xdebug step debugging:

```shell
XDEBUG_MODE=debug docker compose up --build --wait
```

All `composer` commands below run inside the container:

```shell
docker compose exec app <command>
```

## Commands

```shell
# Tests
composer test                  # dump-autoload + PHPUnit (no coverage)
bin/phpunit --filter TestName  # run a single test

# Code style
composer lint                  # PHP-CS-Fixer (fix in place)
composer lint:dry-run          # check only

# Static analysis
composer phpstan               # PHPStan level 8

# Automated refactoring
composer rector                # apply Rector rules
composer rector:dry-run        # preview only

# Database migrations
bin/console doctrine:migrations:migrate
bin/console make:migration     # generate migration from entity changes

# Symfony console
docker compose exec app bin/console <command>
```

## Code Quality

- **PHPStan**: level 8 with `phpstan-symfony`, `phpstan-doctrine`, and `phpstan-deprecation-rules` extensions, plus
  bleeding-edge.
- **PHP-CS-Fixer**: `@Symfony` ruleset with custom overrides (no Yoda comparisons, single-line PHPDocs, one space around
  `.`).
- **Rector**: targets PHP 8.5, PHPUnit 13.1 attribute migration, type declarations. Cache in `var/rector/`.
- **PHPUnit**: PHPUnit unit tests. Cache in `/var/phpunit`.
- **CI** (`.github/workflows/code-quality.yaml`) runs on every PR and on `main`: Rector dry-run, PHP-CS-Fixer dry-run,
  PHPStan, and PHPUnit.

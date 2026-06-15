# AGENTS.md — Fat-Free DevTools

## Purpose

Fat-Free DevTools (`n0nag0n/fatfree-devtools`) is a **local development scaffolding tool** for [Fat-Free Framework](https://fatfreeframework.com/) projects. It provides:

- A `fatfree` CLI binary for serving projects and clearing cache
- A web admin UI for generating controllers, models, routes, and initializing new projects
- Bundled Adminer for database management during development

**This package must never be exposed to the public internet or untrusted networks.**

## Threat model

- Intended use: single developer on localhost
- The web UI can write PHP files, modify routes, and execute Composer commands
- Assume any reachable instance is fully compromised

When changing code, treat all HTTP/CLI input as untrusted even in dev contexts.

## Backwards-compatibility surfaces (do not break)

### CLI (`bin/fatfree`)

| Route | Handler |
|-------|---------|
| `GET / [cli]` | `Devtools_Controller->help` |
| `GET /help [cli]` | `Devtools_Controller->help` |
| `GET /version [cli]` | `Devtools_Controller->version` |
| `GET /serve [cli]` | `Devtools_Controller->serve` |
| `GET /serve/admin [cli]` | `Devtools_Controller->adminServe` |
| `GET /cache/clear [cli]` | `Devtools_Controller->cacheClear` |

### HTTP routes (`config/webtools_config.ini`)

All routes in `[routes]` are public API — including GET-based flows like `/manage-composer` and `/routes/delete/@token`.

### Autoloaded classes

- Global: `CLI`, `ComposerInterfaceAdapter`, `InputValidator`
- Namespace `n0nag0n\`: all controllers, `Project_Config`

### Scaffolding templates (`templates/`)

Placeholder tokens (`#controller_name#`, `#model_name#`, `#table_name#`, `{{ @cnf_* }}`) and generated directory layout must remain stable.

## Project layout

```
bin/fatfree              CLI entry point
config/webtools_config.ini   Web UI routes and globals
controllers/             Web and CLI controllers (n0nag0n\)
models/Project_Config.php    Jig mapper for .fatfree-devtools/project_config
utils/                   CLI helpers, Composer adapter, InputValidator
templates/               Files scaffolded into user projects
ui/                      DevTools HTML templates
public/                  Web root (includes bundled Adminer in public/adminer/)
tests/                   PHPUnit tests
```

## Security rules for contributors

1. **Never interpolate user input into shell commands** without validation and `escapeshellarg()`.
2. **Use `InputValidator`** for hosts, identifiers, relative paths, Composer commands, and allowlists.
3. **Verify filesystem paths** resolve under `PROJECT_BASE_DIR` before read/write/require.
4. **Do not add `| raw`** to UI templates unless content is guaranteed safe.
5. **Preserve existing routes and CLI commands** — harden behavior internally, don't remove surfaces.
6. **Run tests before submitting changes**: `vendor/bin/phpunit`

## Development commands

```bash
composer install
vendor/bin/phpunit
composer audit
fatfree help
fatfree serve admin
```

## Testing focus

Prioritize tests for:

- `utils/InputValidator.php` — pure validation logic
- Route string generation and hashing
- Path traversal rejection
- Shell metacharacter rejection

Full HTTP integration tests are optional; validation unit tests are required for new input handling.
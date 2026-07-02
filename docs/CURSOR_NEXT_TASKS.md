# Bringora Cursor / Codex Next Tasks

## Ground rules

- Do not edit `private_config.php`.
- Do not expose API keys, passwords, database credentials, salts, AppSumo codes, or server secrets.
- Do not rewrite auth, database, or API logic unless the task explicitly says so.
- Keep `Generate/API` diagnostics separate from public website page work.
- Keep deploy green. If a task affects PHP, run `php -l` locally or rely on the GitHub Actions deploy lint.
- Prefer small single-purpose commits.

## Current production URL

- Main app: `https://bringora.barndai.com/index.php`
- Status: `https://bringora.barndai.com/status.php`
- API diagnostics: `https://bringora.barndai.com/api-debug.php`
- Provider test: `https://bringora.barndai.com/provider-test.php`
- App API test: `https://bringora.barndai.com/app-api-test.php`

## Current product state

- Website-first AppSumo MVP shell exists.
- Shared navigation exists in `cloudways/public_html/_nav.php`.
- `My Bringora Brain` stores profile data in browser local storage.
- Main app sends `My Bringora Brain` as optional `local_context` during generation.
- Raw prompts are not saved by default.
- Saved outputs are stored only after the user clicks Save Output.
- Generate/API may still need live debugging.
- Better browser error handling has been added to `index.php` for Generate and Save.
- `provider-test.php` can test one tiny DeepSeek request without printing secrets.
- `app-api-test.php` can test the full `api.php` Generate pipeline without relying on browser JavaScript.

## Public pages already created

- `landing.php`
- `pricing.php`
- `faq.php`
- `roadmap.php`
- `compare.php`
- `use-cases.php`
- `starter-inputs.php`
- `demo-script.php`
- `onboarding.php`
- `checklist.php`
- `support-center.php`
- `privacy.php`
- `terms.php`
- `support.php`
- `status.php`
- `api-debug.php`
- `provider-test.php`
- `app-api-test.php`

## Diagnostic flow for Generate/API

Use this order:

1. Open `/status.php`.
2. If website deploy is not ready, fix deploy first.
3. Open `/api-debug.php`.
4. If API Debug fails, fix server/config/database/cURL first.
5. Open `/provider-test.php` and run the protected provider test.
6. If Provider Test fails, the issue is likely DeepSeek key, model name, provider URL, provider account/billing, timeout, or external network access.
7. Open `/app-api-test.php` and run the protected app API test.
8. If App API Test fails while Provider Test passes, the issue is likely `api.php` auth, usage logging, database table shape, JSON handling, or request shape.
9. If App API Test passes but browser Generate fails, the issue is likely browser-side JavaScript, cookies, blocked fetch behavior, or local browser state.
10. If all diagnostics pass and browser Generate works, remove “Generate parked” language from public pages and status.

## Task 1: Finish navigation coverage

Goal: every user-facing PHP page should include `_nav.php`, except raw machine endpoints such as `api.php`, `save_output.php`, and `health.php`.

Pages known to have shared navigation:

- `index.php`
- `brain.php`
- `saved_outputs.php`
- `privacy.php`
- `terms.php`
- `support.php`
- `support-center.php`
- `checklist.php`
- `status.php`
- `api-debug.php`
- `provider-test.php`
- `app-api-test.php`
- `starter-inputs.php`

Do not change content beyond navigation unless necessary.

## Task 2: API debug output rules

Allowed checks:

- PHP version
- cURL loaded
- PDO loaded
- DB connection ok/fail
- DeepSeek key present yes/no
- DeepSeek URL present yes/no
- DeepSeek model name
- auth type
- daily limit

Do not print actual keys, passwords, salts, full DB password, or beta password.

## Task 3: Provider test rules

`provider-test.php` may run one tiny DeepSeek request.

Allowed output:

- ok/fail state
- HTTP code
- duration
- sanitized provider message

Forbidden output:

- API key
- authorization header
- request headers containing secrets
- full server config
- raw private config

## Task 4: App API test rules

`app-api-test.php` may call `api.php` with a tiny Generate request and show a limited response preview.

Allowed output:

- ok/fail state
- HTTP code
- duration
- sanitized API message
- limited response preview without secrets

Forbidden output:

- beta password
- private config values
- provider API key
- database password
- salts

## Task 5: AppSumo launch polish

Goal: make public pages look like a coherent beta product.

Possible files:

- `pricing.php`
- `faq.php`
- `landing.php`
- `compare.php`
- `support-center.php`

Do not invent final pricing. Keep pricing as draft until owner decides.

## Task 6: Later database/code redemption work

Goal: make AppSumo redemption more production-ready.

Do later only after Generate is stable.

Possible work:

- redemption code status
- used/redeemed timestamp
- tier display
- admin import process
- abuse prevention

## Do not touch yet

- `private_config.php`
- payment system
- mobile app
- heavy account system
- team features

# Bringora Cursor / Codex Next Tasks

## Ground rules

- Do not edit `private_config.php`.
- Do not expose API keys, passwords, database credentials, salts, or AppSumo codes.
- Do not rewrite auth, database, or API logic unless the task explicitly says so.
- Keep `Generate/API` diagnostics separate from public website page work.
- Keep deploy green. If a task affects PHP, run `php -l` locally or rely on the GitHub Actions deploy lint.
- Prefer small single-purpose commits.

## Current production URL

- Main app: `https://bringora.barndai.com/index.php`
- Status: `https://bringora.barndai.com/status.php`
- API diagnostics: `https://bringora.barndai.com/api-debug.php`

## Current product state

- Website-first AppSumo MVP shell exists.
- Shared navigation exists in `cloudways/public_html/_nav.php`.
- `My Bringora Brain` stores profile data in browser local storage.
- Main app sends `My Bringora Brain` as optional `local_context` during generation.
- Raw prompts are not saved by default.
- Saved outputs are stored only after the user clicks Save Output.
- Generate/API may still need live debugging.

## Public pages already created

- `landing.php`
- `pricing.php`
- `faq.php`
- `roadmap.php`
- `compare.php`
- `use-cases.php`
- `demo-script.php`
- `onboarding.php`
- `checklist.php`
- `support-center.php`
- `privacy.php`
- `terms.php`
- `support.php`

## Task 1: Finish navigation coverage

Goal: every user-facing PHP page should include `_nav.php`, except raw machine endpoints such as `api.php`, `save_output.php`, and `health.php`.

Check these pages:

- `brain.php`
- `saved_outputs.php`
- `privacy.php`
- `terms.php`
- `support.php`
- `support-center.php`
- `checklist.php`

Do not change content beyond navigation unless necessary.

## Task 2: Improve API debug output

Goal: make `api-debug.php` identify the blocker without leaking secrets.

Allowed checks:

- PHP version
- cURL loaded
- PDO loaded
- DB connection ok/fail
- DeepSeek key present yes/no
- DeepSeek model name
- auth type
- daily limit

Do not print actual keys, passwords, salts, full DB password, or beta password.

## Task 3: Add safe Generate error details

Goal: replace generic browser `Connection error` with a better visible error when the server returns JSON.

Rules:

- Keep API errors sanitized.
- Do not show secrets.
- Keep fallback message for network failure.

## Task 4: AppSumo launch polish

Goal: make public pages look like a coherent beta product.

Possible files:

- `pricing.php`
- `faq.php`
- `landing.php`
- `compare.php`
- `support-center.php`

Do not invent final pricing. Keep pricing as draft until owner decides.

## Task 5: Later database/code redemption work

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
- provider key handling
- payment system
- mobile app
- heavy account system
- team features

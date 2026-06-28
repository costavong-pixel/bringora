# Security Notes

## Core rules

- Never commit API keys.
- Keep DeepSeek API key in private server config only.
- Backend calls AI provider; browser never calls AI provider directly.
- Use server-side request limits.
- Do not save raw prompt inputs by default.
- Save generated outputs only when the user clicks Save Output.
- Do not store raw IP addresses or raw AppSumo codes in usage logs; use salted hashed access keys.

## Cloudways layout

```text
/cloudways/public_html
  index.php
  api.php

/cloudways/private_html
  private_config.example.php
```

Production private config lives outside public_html.

## Public config rule

If a public config file exists, it must return 403 Forbidden.

## Usage safety

MVP includes or should maintain:

- max input character limit
- max output token limit
- database-backed daily request limit
- database-backed monthly account allowance
- privacy-safe beta usage keys in the `beta_ip:<hash>` format, derived from client IP, user agent, and `USAGE_HASH_SALT`
- privacy-safe AppSumo usage keys in the `appsumo:<hash>` format, derived from the code and `CODE_HASH_SALT` or `USAGE_HASH_SALT`
- generated-output persistence only after an explicit Save Output click
- server-side abuse checks

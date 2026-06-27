# Security Notes

## Core rules

- Never commit API keys.
- Keep DeepSeek API key in private server config only.
- Backend calls AI provider; browser never calls AI provider directly.
- Use server-side request limits.
- Do not save raw user inputs by default.
- Save output only when user clicks Save.

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

MVP should add:

- max input character limit
- max output token limit
- daily request limit
- monthly account allowance
- server-side abuse checks

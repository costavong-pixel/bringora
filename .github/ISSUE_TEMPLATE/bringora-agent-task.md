---
name: Bringora Agent Task
about: Safe follow-up task created from Bringora production status
---

## Source

Status JSON:

```text
https://bringora.barndai.com/status-json.php
```

Status page:

```text
https://bringora.barndai.com/status.php
```

## Task

Do one small safe task only.

## Safety rules

- Do not edit `private_config.php`.
- Do not print or expose API keys, database passwords, salts, beta passwords, GitHub secrets, or AppSumo codes.
- Do not make destructive database changes.
- Do not change payment logic.
- Do not rewrite auth logic unless this issue explicitly asks for it.
- Prefer branch + PR for risky changes.

## Generate/API debug order

If this is related to Generate/API, follow this order:

```text
/status.php
/api-debug.php
/db-debug.php
/provider-test.php
/app-api-test.php
/browser-api-test.php
/index.php
```

## Expected output

- Explain what changed.
- Include commit or PR link.
- State whether a blocker remains.

# Bringora Cloudways Deployment Checklist

## Goal

Deploy the Bringora private beta web app to Cloudways safely.

## Current baseline

The main branch includes:

- Cloudways PHP beta app
- DeepSeek direct API flow
- database-backed usage limits
- saved outputs
- privacy, terms, and support pages

## File layout

Upload files like this:

```text
cloudways/public_html/*  -> Cloudways public_html/
cloudways/private_html/* -> Cloudways private_html/
```

Keep private configuration outside public_html.

## Private config

On the server, copy:

```text
cloudways/private_html/private_config.example.php
```

to:

```text
cloudways/private_html/private_config.php
```

Then fill in the real private values on the server only.

Required private values:

```text
BETA_PASSWORD
DEEPSEEK_SECRET
DB_HOST
DB_NAME
DB_USER
DB_PASSWORD
USAGE_HASH_SALT
CODE_HASH_SALT
SUPPORT_EMAIL
```

Use long random private values for the salts.

## Database

Run this SQL file in the Cloudways database:

```text
database/schema.sql
```

It prepares:

```text
users
saved_outputs
usage_logs
redemption_codes
```

## First test

1. Open the app URL.
2. Log in with beta password.
3. Try empty input and confirm a safe error.
4. Try a normal input.
5. Confirm DeepSeek returns text.
6. Confirm usage count increases.
7. Click Save Output.
8. Open Saved Outputs.
9. Delete the saved output.
10. Open Privacy, Terms, and Support.

## Privacy behavior

- Raw prompt input is not saved by default.
- Generated output is saved only after Save Output is clicked.
- Usage tracking uses hashed access keys.
- Raw IP is not stored in usage logs.
- Raw AppSumo code is not stored in access_key.

## Later production work

- Real user accounts
- Password reset
- Email verification
- AppSumo webhook flow
- Admin dashboard
- Stronger abuse controls
- Error monitoring

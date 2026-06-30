# Logto Authentication Architecture

## Source

GitHub: https://github.com/logto-io/logto  
Website: https://logto.io

## What it is

Logto is an authentication and authorization system for SaaS apps.

It can handle:

- Login / signup
- Password reset
- Email verification
- Google / social login
- OAuth / OIDC
- SSO
- Organizations
- Multi-tenancy
- RBAC roles and permissions
- API authentication

## Why Bringora may need it

Bringora is not just a simple chatbot. If it becomes a SaaS product, it needs:

- User accounts
- Paid accounts
- Agency accounts
- Staff access
- Client access
- Admin dashboard
- Permission control

Building this from scratch wastes development time and creates security risk.

## Bringora use case

Possible user structure:

```text
Platform Owner
  → Agency Account
      → Staff Users
      → Client Users

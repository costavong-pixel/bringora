# Bringora

**Handy Ora, always with you.**

Bringora is an AI daily thinking companion for housewives, students, solo entrepreneurs, and people who struggle to organize thoughts clearly.

Bringora is **not** a prompt tool, not a video generator, and not an image generator. It produces **text-only strategy, structure, notes, plans, and next actions**.

## Product Promise

> From messy thoughts to clear action.

## Core Rule

One input -> one selected problem -> one structured output -> one next action.

No multi-output dumping. No confusing dropdowns. No Notion-style setup complexity.

## MVP Categories

1. Help Me Write or Reply
2. Help Me Understand
3. Help Me Decide
4. Make a Step-by-Step Plan
5. Help Me Promote or Sell
6. Turn My Idea Into Something
7. Organize My Notes

## Tech Direction

- Web-first MVP for AppSumo validation
- Cloudways-compatible PHP first
- DeepSeek direct API only
- Text-only output
- No API keys in GitHub
- App Store / Play Store later after validation

## Repository Structure

```text
/docs                 Product specs and roadmap
/cloudways            Cloudways PHP MVP scaffold
/database             SQL schema and migration notes
```

## Immediate Build Goal

Build a working beta web app where a user selects a daily problem card, pastes messy thoughts, and receives one structured output with a clear next best action.


## Cloudways MVP Setup

1. Copy `cloudways/private_html/private_config.example.php` to `cloudways/private_html/private_config.php` on the server.
2. Set `BETA_PASSWORD`, `DEEPSEEK_SECRET`, `SUPPORT_EMAIL`, and any `APPSUMO_CODES` in the private config file.
3. Point Cloudways public web root to `cloudways/public_html`.
4. Open `index.php`, log in with the beta password or an AppSumo redemption code, choose one card, paste messy thoughts, and generate one structured text output.

The browser never receives the DeepSeek secret. The MVP enforces a session login, CSRF token, max input length, and daily beta or AppSumo tier request limit before calling DeepSeek.

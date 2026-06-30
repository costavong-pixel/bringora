# Future Feature: WhatsApp Channel for Ora

## Status

Not implementing now.

This feature should be considered only after Bringora has enough customer base to justify WhatsApp setup, support, and Meta API complexity.

## Core Idea

Ora should be available inside WhatsApp, but WhatsApp should only be a channel, not the main product.

Customers will use regular WhatsApp.

Businesses will connect through WhatsApp Business Cloud API.

## Architecture

```text
Customer regular WhatsApp
→ WhatsApp Business Cloud API
→ n8n or Bringora WhatsApp adapter
→ Bringora / Ora brain
→ Reply back through WhatsApp
→ Customer receives message in regular WhatsApp
```

## What to Reuse From Existing n8n WhatsApp Workflows

Only reuse the WhatsApp integration layer:

- WhatsApp trigger / webhook
- WhatsApp Business Cloud credentials
- Incoming message parser
- Send message node
- Error handling
- Template fallback logic

Do not reuse the full customer-support workflow logic.

## Important Rule

Ora brain must stay inside Bringora.

WhatsApp is only a transport layer.

The same Ora brain should later support:

- Website chat
- WhatsApp
- Instagram DM
- Facebook Messenger
- SMS
- Email
- Voice call

## Customer-Side Explanation

Customers do not need WhatsApp Business.

Customers use normal WhatsApp like usual.

The business side uses WhatsApp Business Cloud API behind the scenes.

## Business Value

This feature can make Bringora easier to sell because many customers already message businesses on WhatsApp.

Possible niche:

> AI WhatsApp receptionist for local businesses.

Good target customers:

- Restaurants
- Salons
- Clinics
- Contractors
- Tutors
- Real estate agents
- Small ecommerce stores
- Franchise locations

## V1 Implementation Later

Fast version:

```text
WhatsApp Cloud API
→ n8n
→ Bringora API
→ Ora reply
→ n8n sends WhatsApp message
```

Use n8n as a bridge first because it is faster to test and easier to adjust.

## V2 Implementation Later

Native version:

```text
WhatsApp Cloud API
→ Bringora WhatsApp Adapter
→ Ora Core
→ WhatsApp Cloud API
```

Build this only after demand is proven and the WhatsApp channel becomes important enough to justify custom backend code.

## Do Not Build Yet

Reasons:

- Meta setup takes time.
- Business API approval can be annoying.
- WhatsApp templates have rules.
- Support burden is higher.
- Better to prove Bringora first through website chat or simpler channels.

## Future Implementation Notes

When this becomes active, design it as a channel adapter:

```text
channel_inbound_message
→ normalize message format
→ send to Ora core
→ receive Ora response
→ format for WhatsApp
→ send through WhatsApp Cloud API
```

Avoid hard-coding WhatsApp-specific logic into Ora core.

Ora core should not care where the message came from.

## Product Positioning

Do not sell this as a WhatsApp bot only.

Position it as:

> Bringora lets one business AI assistant answer customers across different channels. WhatsApp is one future channel.

This keeps the product bigger than WhatsApp and avoids building a feature that only works for one messaging platform.

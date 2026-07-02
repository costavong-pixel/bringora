# Bringora n8n Agent Watcher

## Goal

Use n8n to check Bringora every 5 minutes and create one GitHub issue when the production site is ready for the next safe task.

This does not make ChatGPT wake up automatically. It creates a clean task in GitHub that a coding agent or human can pick up.

## Endpoint

Use this machine-readable endpoint:

```text
https://bringora.barndai.com/status-json.php
```

Key fields:

```json
{
  "website_ready": true,
  "agent_ready": true,
  "recommended_action": "create_github_issue",
  "github_issue": {
    "dedupe_key": "...",
    "title": "Bringora agent task ...",
    "body": "..."
  }
}
```

## n8n workflow nodes

### 1. Schedule Trigger

Set interval:

```text
Every 5 minutes
```

### 2. HTTP Request — Get Bringora status

Method:

```text
GET
```

URL:

```text
https://bringora.barndai.com/status-json.php
```

Response format:

```text
JSON
```

### 3. IF — Is agent ready?

Condition:

```text
{{$json.agent_ready}} is true
```

False path:

```text
Stop / do nothing
```

True path:

```text
Continue
```

### 4. Data Store or static data — Dedupe

Store this value after a successful issue creation:

```text
{{$json.github_issue.dedupe_key}}
```

Before creating an issue, compare the current key with the stored last key.

If same:

```text
Stop / do nothing
```

If different:

```text
Create issue
```

This avoids creating a new issue every 5 minutes for the same deploy.

### 5. GitHub — Create Issue

Repository:

```text
costavong-pixel/bringora
```

Title:

```text
{{$json.github_issue.title}}
```

Body:

```text
{{$json.github_issue.body}}
```

Optional label:

```text
agent-ready
```

Only use the label if it already exists in GitHub. If the label does not exist, leave labels empty.

### 6. Update dedupe value

After GitHub issue creation succeeds, save:

```text
{{$json.github_issue.dedupe_key}}
```

as the last processed key.

## Safer first version

For the first test, do not connect a coding agent yet.

Use:

```text
Schedule Trigger → HTTP Request → IF → GitHub Create Issue
```

Then manually confirm that only one issue is created per deploy.

## Later coding-agent version

After the watcher works:

```text
n8n creates issue
coding agent watches issues
agent creates branch
agent opens PR
GitHub Actions checks PR
human merges
Cloudways deploys
```

Do not let the agent edit these without review:

```text
private_config.php
payment logic
auth logic
database destructive changes
GitHub secrets
```

## Recommended issue wording

The issue should tell the agent:

```text
Do one small safe task only.
Do not expose secrets.
Do not edit private_config.php.
Prefer branch + PR for risky changes.
If Generate/API is failing, follow status.php diagnostic path first.
```

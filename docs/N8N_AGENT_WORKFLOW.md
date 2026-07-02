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

## Minimum safe workflow

Build this first:

```text
Schedule Trigger
→ HTTP Request
→ IF agent_ready true
→ GitHub Create Issue
```

Do not connect a coding agent until this basic watcher is proven.

## n8n setup: exact node settings

### 1. Schedule Trigger

Node:

```text
Schedule Trigger
```

Interval:

```text
Every 5 minutes
```

Keep the workflow inactive until you test manually.

### 2. HTTP Request — Get Bringora status

Node:

```text
HTTP Request
```

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

Expected test result should include:

```text
website_ready
agent_ready
github_issue.dedupe_key
github_issue.title
github_issue.body
```

### 3. IF — Is agent ready?

Node:

```text
IF
```

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

### 4. GitHub — Create Issue

Node:

```text
GitHub
```

Operation:

```text
Issue → Create
```

Owner:

```text
costavong-pixel
```

Repository:

```text
bringora
```

Title:

```text
{{$json.github_issue.title}}
```

Body:

```text
{{$json.github_issue.body}}
```

Labels:

```text
leave empty first
```

Only add `agent-ready` after the label exists in GitHub.

## First test procedure

1. Keep workflow inactive.
2. Execute workflow manually once.
3. Confirm the HTTP Request node receives JSON.
4. Confirm the IF node goes true only when `agent_ready` is true.
5. Let the GitHub node create one issue.
6. Disable the workflow again.
7. Check GitHub Issues and confirm the title/body are correct.

## Dedupe: stop duplicate issues

Without dedupe, the workflow can create the same issue every 5 minutes.

Use one of these methods.

### Method A: n8n Data Store

Use this when your n8n version has Data Store.

Flow:

```text
Schedule Trigger
→ HTTP Request
→ IF agent_ready true
→ Data Store Get last key
→ IF current key differs
→ GitHub Create Issue
→ Data Store Set last key
```

Key name:

```text
bringora_last_dedupe_key
```

Value to compare:

```text
{{$json.github_issue.dedupe_key}}
```

If current key equals saved key:

```text
Stop
```

If current key differs:

```text
Create GitHub issue
```

After GitHub issue succeeds, set:

```text
bringora_last_dedupe_key = {{$json.github_issue.dedupe_key}}
```

### Method B: GitHub search before create

Use this if Data Store is not available.

Flow:

```text
Schedule Trigger
→ HTTP Request
→ IF agent_ready true
→ GitHub Search Issues
→ IF no issue title contains dedupe_key
→ GitHub Create Issue
```

Search query idea:

```text
repo:costavong-pixel/bringora is:issue "{{$json.github_issue.dedupe_key}}"
```

If search finds an issue:

```text
Stop
```

If search finds nothing:

```text
Create issue
```

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

## Safe activation rule

Only activate the 5-minute schedule after these are true:

```text
1. status-json.php returns valid JSON
2. one manual test creates one correct GitHub issue
3. dedupe is working
4. the workflow does nothing when the dedupe key is unchanged
```

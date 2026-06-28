# GitHub Actions Cloudways Deployment

## Purpose

This manual workflow deploys Bringora from GitHub to Cloudways.

It is useful when we want this flow:

```text
merge to GitHub -> run manual deploy workflow -> Cloudways updates
```

## Workflow file

```text
.github/workflows/deploy-cloudways.yml
```

## Required repository secrets

Create these in GitHub:

```text
Settings -> Secrets and variables -> Actions -> New repository secret
```

Required names:

```text
CLOUDWAYS_HOST
CLOUDWAYS_USERNAME
CLOUDWAYS_PORT
CLOUDWAYS_SSH_KEY
CLOUDWAYS_APP_PATH
```

## Values

`CLOUDWAYS_HOST` is the Cloudways server IP or host.

`CLOUDWAYS_USERNAME` is the Cloudways SSH/SFTP username.

`CLOUDWAYS_PORT` is usually `22`, unless Cloudways shows a different port.

`CLOUDWAYS_SSH_KEY` is the SSH key used by the workflow to connect to Cloudways. Keep it only inside GitHub Actions secrets.

`CLOUDWAYS_APP_PATH` is the application folder, for example:

```text
/applications/YOUR_APP_ID
```

## What deploys

The workflow copies:

```text
cloudways/public_html/*  -> $CLOUDWAYS_APP_PATH/public_html/
cloudways/private_html/* -> $CLOUDWAYS_APP_PATH/private_html/
```

The workflow does not overwrite:

```text
private_config.php
```

## How to run

1. Open GitHub Actions.
2. Choose `Deploy Bringora to Cloudways`.
3. Click `Run workflow`.
4. Type `DEPLOY` in the confirm box.
5. Run from the `main` branch.

## After running

Open:

```text
https://YOUR_DOMAIN/health.php
```

Expected response:

```text
ok
```

Then test:

```text
login -> generate -> usage count -> save output -> view saved output -> delete saved output
```

## Safety notes

- Manual run only.
- No real credentials are in the repository.
- Server-only config stays outside GitHub.
- The deploy workflow does not overwrite `private_config.php`.

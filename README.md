# Aurora

A Craft CMS 5 starter project with Tailwind.

![Logo](aurora.jpg)

**This project uses:**

- [Vite](https://vitejs.dev/)
- [Craft CMS](https://craftcms.com/docs/4.x/)
- [DDEV](https://ddev.readthedocs.io/en/stable/)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Alpine.js](https://alpinejs.dev/)
- [Atomic Design](https://atomicdesign.bradfrost.com/table-of-contents/)

## Installation

**1. Start DDEV, Install Craft CMS and dependencies**

After cloning, firstly, edit `.ddev/config.yaml` and change the `name` to your project name and also change `additional_fqdns`.

Then, there are a few CLI commands ([See more](#cli-commands)) we've created that allow starting DDEV, installing Craft CMS and installing dependencies (Node particularly). The one to get you started:

```shell
make install
```

**2. Import DB**

`ddev import-db --file=aurora-db.sql` OR configure Swiff and use `swiff --databasepull` OR `ddev import-db --file=aurora-db.sql`

**3. Starting Vite**

Once you've followed step 1 and it's successfully ran through the steps, you'll need to start Vite which allows you to start using front end tooling:

```shell
make dev
```

## CLI commands

We've create a few commands to make development easier. All these commands are ran in terminal:

| Command        | Description                                                                     |
| -------------- | ------------------------------------------------------------------------------- |
| `make install` | Starts DDEV, Install Craft CMS and dependencies.                                |
| `make setup`   | Use when starting to work on your project especially if your working in a team. |
| `make dev`     | Starts Vite development process.                                                |
| `make prod`    | Run on production to start Vite build process - minify, compress etc.           |
| `make clean`   | Removes composer and node files ready for a clean install.                      |
| `make update`  | Smaller command that runs `ddev exec php craft update all`.                     |
| `make up` 💅   | Runs project config apply and migrations apply.                                 |

## Requirements

- **Node:** > v20
- **MYSQL** > v8.0

## Documentation

### Heading Hierarchy

- [Heading State System](https://simple-team.atlassian.net/wiki/spaces/AU/blog/2026/01/28/2952429575/Heading+State+System+Automatic+SEO-Friendly+Headings)

## Bitbucket Pipelines (CI/CD)

Deployments are automated via Bitbucket Pipelines using a **symlink-based release** strategy (similar to Capistrano/Deployer). Pushing to the `staging` or `production` branch triggers a deployment to the corresponding environment.

### Deployment Process

The deploy step in the Pipeline does the following:

1. **Package** — Bundles `config/`, `modules/`, `templates/`, `vendor/`, `web/`, `bootstrap.php`, `composer.json`, `composer.lock`, and `craft` into a `release.tar.gz` archive. The `web/.htaccess` is excluded (the server's own copy is used instead).
2. **Upload** — SCP transfers the archive to the server's `deployment/` directory.
3. **Extract & Setup** — Via SSH, the archive is extracted and moved to `releases/release-<build-number>`.
4. **Link Persistent Files** — Copies `.env` and `web/.htaccess` from the `persist/` directory, and symlinks `storage/` and `web/assets/` so uploads and generated files survive across releases.
5. **Run Craft Commands** — Backs up the database, runs migrations, applies project config, and clears caches.
6. **Activate** — Atomically swaps the `current` symlink to point at the new release, then updates the `public` symlink.
7. **Cleanup** — Removes old releases and old database backups, keeping only the 10 most recent of each.

### Server Directory Structure

```
$PATH/
├── current → releases/release-<latest>   # Active release (symlink)
├── public → current/public               # Web root (symlink)
├── deployment/                            # Temporary upload area
├── releases/
│   ├── release-100/
│   ├── release-101/
│   └── release-102/
└── persist/                               # Shared across all releases
    ├── .env
    ├── storage/
    └── web/
        ├── .htaccess
        └── assets/
```

### Environment Variables

Each environment (staging/production) requires these Bitbucket repository variables:

| Variable                                   | Description                                     |
| ------------------------------------------ | ----------------------------------------------- |
| `$STAGE_USER` / `$PROD_USER`               | SSH username                                    |
| `$STAGE_HOST` / `$PROD_HOST`               | Server hostname                                 |
| `$STAGE_PATH` / `$PROD_PATH`               | Absolute path to the project root on the server |
| `$STAGE_PHP_VERSION` / `$PROD_PHP_VERSION` | PHP version identifier (e.g. `8.2`)             |

### Custom Pipelines

There are also manually-triggered custom pipelines available in Bitbucket for testing deployment steps.

---

## 📝 Changelog Guidelines

All updates should be recorded in `CHANGELOG.md`. Follow this format to keep things clear, consistent, and helpful for everyone.

Use bullet points like:

```md
## Unreleased

- Added feature or improvement.
- Changed behavior or config.
- Removed deprecated functionality.
- Fixed bugs or issues.
```

### ✅ When to use each prefix:

| Prefix    | Use it for...                                                                  |
| --------- | ------------------------------------------------------------------------------ |
| `Added`   | New features, modules, configs,functionality introduced                        |
| `Changed` | Updates to existing behavior, templates, styles, config or content structure   |
| `Removed` | Deprecated or removed files, features, or plugins                              |
| `Fixed`   | Bug fixes or patches                                                           |
| `Devops`  | Backend/deployment/config updates like CMS version bumps, DB syncs, or tooling |

### Deploy

When code is deployed to staging or production via SWIFF, document it in `CHANGELOG.md` which branch it is deployed and where.

| Prefix       | Use it for...                           |
| ------------ | --------------------------------------- |
| `Staging`    | Deployed `develop` branch to Production |
| `Production` | Deployed `main` branch to Production    |

---

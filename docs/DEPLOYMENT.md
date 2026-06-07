# Deployment Guide

> Developed by Agência Upgrade — https://agenciaupgrade.com.br

## Overview

Canopy uses GitHub Actions for automated deployment to production. Two workflows handle:

1. **`deploy-production.yml`** — Triggered on `push` to `main` branch
2. **`auto-update.yml`** — Runs weekly (Monday 07:00 UTC) to update dependencies

## Prerequisites

### Production Server

- SSH access (key-based authentication)
- PHP 8.5+ with extensions: curl, xml, openssl, gd, mysql
- MySQL 8.0+ or MariaDB 11+
- Redis (recommended) or alternative caching solution
- Composer globally installed
- WP-CLI globally installed
- Domain pointed and SSL certificate issued (Let's Encrypt recommended)

### GitHub Repository

Repository settings → Secrets and variables → Actions

Add these secrets:

| Secret | Value | Example |
|---|---|---|
| `PROD_SSH_HOST` | Production server hostname | `example.com` or `192.168.1.100` |
| `PROD_SSH_USER` | SSH user | `deploy` or `ubuntu` |
| `PROD_SSH_KEY` | SSH private key (full content) | `-----BEGIN OPENSSH PRIVATE KEY-----...` |
| `PROD_SSH_PORT` | SSH port | `22` (or custom) |
| `PROD_WP_PATH` | Project root on server | `/home/deploy/example.com` |
| `CF_ZONE_ID` | Cloudflare Zone ID (optional) | From Cloudflare dashboard |
| `CF_API_TOKEN` | Cloudflare API token (optional) | Token with `cache_purge` permission |

## Deployment Workflow

### On `push` to `main`

**File: `.github/workflows/deploy-production.yml`**

1. **GitHub Actions runner** checks out code
2. **SSH into production server**
3. **`git fetch origin main`** — fetch latest remote
4. **`git reset --hard origin/main`** — checkout exact main branch state
5. **`composer install --no-dev --optimize-autoloader`** — install PHP dependencies
6. **`wp rewrite flush`** — regenerate WordPress rewrite rules
7. **`wp cache flush`** — clear Redis object cache
8. **`Timber\Cache\Cleaner::clear_cache_timber()`** — clear Twig template cache (critical)
9. **Purge Cloudflare cache** — invalidate all cached assets (if secrets provided)

### Manual Deployment

If you prefer to deploy without GitHub Actions:

```bash
# From your local machine
git push origin main

# OR manually on server
ssh deploy@example.com
cd /home/deploy/example.com

git fetch origin
git reset --hard origin/main
composer install --no-dev --optimize-autoloader
wp rewrite flush --allow-root
wp cache flush --allow-root
wp eval 'Timber\Cache\Cleaner::clear_cache_timber();' --allow-root
```

## Weekly Auto-Update Workflow

**File: `.github/workflows/auto-update.yml`**

Runs every Monday at 07:00 UTC:

1. **`composer update`** — respects version constraints in `composer.json`
   - WordPress: `^7.0` (follows minor/patch, blocks MAJOR)
   - Other packages: per their constraints
2. **Check WordPress major version change** — if MAJOR bumped:
   - Alert message in logs
   - **Does NOT auto-deploy if MAJOR changed** (requires manual review)
3. **If `composer.lock` changed:**
   - Commit to `main` with message "chore: update dependencies"
   - Push to origin
   - Auto-deploy via deploy-production.yml (unless MAJOR WP changed)

### Monitoring

Check GitHub Actions → `Auto-Update Dependencies` → Latest run for:
- ✅ Update succeeded
- ⚠️ WordPress major version alert (manual review needed)
- 📊 Deployment status

## .env on Production

The `.env` file **is not versioned** in Git. You must create it on the server once:

```bash
ssh deploy@example.com
cd /home/deploy/example.com

# Copy template
cp .env.example .env

# Edit with production values
nano .env
```

Fill in:
```
WP_ENV=production
WP_HOME=https://example.com
WP_SITEURL=https://example.com/wp
DB_NAME=prod_database
DB_USER=prod_user
DB_PASSWORD=secure_password
DB_HOST=localhost

# Salts from https://roots.io/salts.html
AUTH_KEY='...'
SECURE_AUTH_KEY='...'
# ... etc

WP_REDIS_HOST=localhost
WP_REDIS_PORT=6379

# Transactional email (if not using Mailpit)
SMTP_HOST=mail.example.com
SMTP_PORT=465
SMTP_USER=noreply@example.com
SMTP_PASS=email_password
SMTP_FROM=noreply@example.com
SMTP_FROM_NAME='Example Site'
```

After each deployment, the `.env` persists (not overwritten by `git reset`).

## Verification Checklist

After deployment:

- [ ] Visit `https://example.com` — homepage loads
- [ ] Check `https://example.com/wp/wp-admin` — login works
- [ ] View WordPress console logs: `tail -f /var/log/php-fpm.log`
- [ ] Check Redis cache: `redis-cli ping` → should return `PONG`
- [ ] Verify Timber cache cleared: `web/app/themes/canopy/cache/twig/` is empty or absent (it is recreated on first render; the deploy step clears it)
- [ ] Test form submission (if any) — SMTP working?
- [ ] Check Lighthouse score: DevTools → Lighthouse (should be ≥ 95)

## Rollback

If deployment fails or causes issues:

```bash
ssh deploy@example.com
cd /home/deploy/example.com

# View recent commits
git log --oneline -10

# Rollback to previous version
git reset --hard <commit-hash>

# Redeploy
composer install --no-dev --optimize-autoloader
wp cache flush --allow-root
wp eval 'Timber\Cache\Cleaner::clear_cache_timber();' --allow-root
```

Or revert on GitHub:

1. Create a new commit that reverts the problematic changes
2. Push to `main`
3. GitHub Actions automatically deploys the revert

## Debugging Deployment Failures

### Check GitHub Actions logs

Repository → Actions → Latest workflow run → View details

Look for:
- SSH connection errors
- Composer install failures
- WordPress CLI errors

### SSH into server and check manually

```bash
ssh deploy@example.com
cd /home/deploy/example.com

# Check git status
git log -1 --oneline
git status

# Test composer
composer update --dry-run  # Don't actually update, just check

# Check error logs
tail -f /var/log/php-fpm.log
tail -f /var/log/nginx/error.log

# Test WordPress
wp eval 'echo "WordPress is working";' --allow-root
wp db query 'SELECT 1;' --allow-root
```

### Common Issues

**"fatal: Could not read from remote repository"**
- SSH key missing or invalid
- Check `PROD_SSH_KEY` secret

**"composer install fails"**
- Check PHP version: `php -v`
- Check composer.json for syntax errors
- Run `composer install` locally first

**"wp rewrite flush fails"**
- Check WP_HOME and WP_SITEURL in `.env`
- Verify database connection
- Check MySQL credentials

**"Timber cache not clearing"**
- Verify `web/app/themes/canopy/cache/` directory exists and is writable
- Check file permissions: `chmod -R 755 web/app/themes/canopy/cache/`

**"Cloudflare cache purge fails"**
- Verify Zone ID and API token (test with `curl`)
- Check API token has `cache_purge` permission

## Best Practices

1. **Test locally first** — Use Docker before pushing to main
2. **Run composer lint** — `composer lint` before pushing
3. **Review GitHub Actions logs** — After every deploy, check for warnings
4. **Monitor error logs** — `tail -f /var/log/php-fpm.log` on server
5. **Keep `.env` safe** — Never commit it; only on server
6. **Backup database** — Before major WordPress updates
7. **Stagger deployments** — Avoid deploying during peak traffic

## Advanced Setup

### Scheduled Backups

Add to production server's crontab:

```bash
# Daily database backup (3 AM)
0 3 * * * wp db export /backups/db-$(date +\%Y-\%m-\%d).sql --allow-root

# Weekly full backup (Sunday 2 AM)
0 2 * * 0 tar -czf /backups/full-$(date +\%Y-\%m-\%d).tar.gz /home/deploy/example.com --exclude=node_modules --exclude=vendor

# Keep only 30 days of backups
0 4 * * * find /backups -name "*.sql" -mtime +30 -delete
0 4 * * * find /backups -name "*.tar.gz" -mtime +30 -delete
```

---

## Resources

- [GitHub Actions Docs](https://docs.github.com/en/actions)
- [WP-CLI Manual](https://wp-cli.org/)
- [Cloudflare API](https://api.cloudflare.com/)
- [Timber Cache Clear](https://timber.github.io/docs/v2/reference/)

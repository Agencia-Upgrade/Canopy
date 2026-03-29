#!/usr/bin/env bash
# =============================================================================
# start.sh — Canopy project setup
# Agência Upgrade — https://agenciaupgrade.com.br
# =============================================================================
# Run once when creating a new project from this boilerplate.
# Renames theme, replaces namespaces/prefixes, and generates .env.
# =============================================================================

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
BOLD='\033[1m'
NC='\033[0m'

echo ""
echo -e "${BOLD}Canopy — Project Setup${NC}"
echo -e "Agência Upgrade — https://agenciaupgrade.com.br"
echo "────────────────────────────────────────────────"
echo ""

# Guard: must be run from project root
if [ ! -f "composer.json" ] || [ ! -d "web/app/themes/canopy" ]; then
    echo -e "${RED}Error: run this script from the project root (where composer.json is).${NC}"
    exit 1
fi

# ─── Collect inputs ──────────────────────────────────────────────────────────

read -p "Project slug (lowercase, hyphens) [my-site]: " PROJECT_SLUG
PROJECT_SLUG="${PROJECT_SLUG:-my-site}"

# Derive default theme name from slug: my-site → My Site
DEFAULT_THEME_NAME=$(echo "$PROJECT_SLUG" | sed 's/-/ /g; s/\b\(.\)/\u\1/g')

# Derive namespace from slug: my-site → MySite
PROJECT_NAMESPACE=$(echo "$PROJECT_SLUG" | sed 's/-\([a-z]\)/\U\1/g; s/^\([a-z]\)/\U\1/')

# Derive CSS prefix from first letters of each word (up to 3 chars): my-site → ms
PROJECT_PREFIX=$(echo "$PROJECT_SLUG" | tr '-' '\n' | head -3 | cut -c1 | tr -d '\n')

echo ""
echo -e "${BLUE}Theme name (shown in WordPress admin) [${DEFAULT_THEME_NAME}]:${NC}"
read -p "> " THEME_NAME
THEME_NAME="${THEME_NAME:-$DEFAULT_THEME_NAME}"

echo ""
echo -e "${BLUE}CSS prefix (2-4 chars, no dash) [${PROJECT_PREFIX}]:${NC}"
read -p "> " CSS_PREFIX
CSS_PREFIX="${CSS_PREFIX:-$PROJECT_PREFIX}"

echo ""
echo -e "${BOLD}Environment:${NC}"
echo "  1) Local (Docker)"
echo "  2) Staging server"
echo "  3) Production server"
echo ""
read -p "Choose [1]: " ENV_CHOICE
ENV_CHOICE="${ENV_CHOICE:-1}"

if [ "$ENV_CHOICE" = "1" ]; then
    WP_ENV="development"
    WP_HOME="http://localhost"
    INSTALL_PATH=""
    DB_HOST="db"
    DB_USER_DEFAULT="dev"
    DB_PASSWORD_DEFAULT="dev"
    DB_NAME_DEFAULT="${PROJECT_SLUG//-/_}_local"
else
    if [ "$ENV_CHOICE" = "2" ]; then
        WP_ENV="staging"
    else
        WP_ENV="production"
    fi

    echo ""
    echo -e "${BLUE}Domain (without https://) [${PROJECT_SLUG}.com.br]:${NC}"
    read -p "> " DOMAIN
    DOMAIN="${DOMAIN:-${PROJECT_SLUG}.com.br}"
    WP_HOME="https://${DOMAIN}"

    echo ""
    echo -e "${BLUE}Install path on server (e.g. /home/user/domains/${DOMAIN}/bedrock):${NC}"
    read -p "> " INSTALL_PATH
    INSTALL_PATH="${INSTALL_PATH:-/home/user/domains/${DOMAIN}/bedrock}"

    DB_HOST_DEFAULT="localhost"
    echo ""
    echo -e "${BLUE}Database host [${DB_HOST_DEFAULT}]:${NC}"
    read -p "> " DB_HOST
    DB_HOST="${DB_HOST:-$DB_HOST_DEFAULT}"

    DB_USER_DEFAULT="root"
    DB_PASSWORD_DEFAULT=""
    DB_NAME_DEFAULT="${PROJECT_SLUG//-/_}"
fi

echo ""
echo -e "${BLUE}Database name [${DB_NAME_DEFAULT}]:${NC}"
read -p "> " DB_NAME
DB_NAME="${DB_NAME:-$DB_NAME_DEFAULT}"

echo ""
read -p "Database user [${DB_USER_DEFAULT}]: " DB_USER
DB_USER="${DB_USER:-$DB_USER_DEFAULT}"

echo ""
read -s -p "Database password [${DB_PASSWORD_DEFAULT:-leave blank}]: " DB_PASSWORD
echo ""
DB_PASSWORD="${DB_PASSWORD:-$DB_PASSWORD_DEFAULT}"

echo ""
echo -e "${BLUE}Cloudflare Images base URL (Enter to skip):${NC}"
read -p "> " CF_IMAGES_URL

echo ""
echo "────────────────────────────────────────────────"
echo -e "${BOLD}Summary${NC}"
echo ""
echo -e "  Project slug:  ${GREEN}${PROJECT_SLUG}${NC}"
echo -e "  PHP namespace: ${GREEN}${PROJECT_NAMESPACE}\\\\${NC}"
echo -e "  CSS prefix:    ${GREEN}${CSS_PREFIX}-${NC}"
echo -e "  Theme name:    ${GREEN}${THEME_NAME}${NC}"
echo -e "  Environment:   ${GREEN}${WP_ENV}${NC}"
echo -e "  WP_HOME:       ${GREEN}${WP_HOME}${NC}"
if [ -n "$INSTALL_PATH" ]; then
echo -e "  Install path:  ${GREEN}${INSTALL_PATH}${NC}"
fi
echo -e "  DB name:       ${GREEN}${DB_NAME}${NC}"
echo -e "  DB user:       ${GREEN}${DB_USER}${NC}"
echo ""
read -p "Proceed? [Y/n]: " CONFIRM
CONFIRM="${CONFIRM:-Y}"
if [[ ! "$CONFIRM" =~ ^[Yy]$ ]]; then
    echo "Aborted."
    exit 0
fi

echo ""

# ─── 1. Rename theme folder ───────────────────────────────────────────────────

if [ "$PROJECT_SLUG" != "canopy" ]; then
    echo -e "  Renaming theme ${YELLOW}canopy${NC} → ${GREEN}${PROJECT_SLUG}${NC}..."
    mv "web/app/themes/canopy" "web/app/themes/${PROJECT_SLUG}"
fi

THEME_DIR="web/app/themes/${PROJECT_SLUG}"

# ─── 2. Replace namespace ────────────────────────────────────────────────────

echo -e "  Replacing namespace ${YELLOW}Canopy\\\\${NC} → ${GREEN}${PROJECT_NAMESPACE}\\\\${NC}..."

find "$THEME_DIR/src" -name "*.php" -exec \
    sed -i "s/namespace Canopy;/namespace ${PROJECT_NAMESPACE};/g; s/namespace Canopy\\\\/namespace ${PROJECT_NAMESPACE}\\\\/g" {} +

# Update use statement in functions.php
sed -i "s/use Canopy\\\\Site;/use ${PROJECT_NAMESPACE}\\\\Site;/g" "$THEME_DIR/functions.php"

# Update PSR-4 in composer.json
sed -i "s|\"Canopy\\\\\\\\\": \"web/app/themes/canopy/src/\"|\"${PROJECT_NAMESPACE}\\\\\\\\\": \"web/app/themes/${PROJECT_SLUG}/src/\"|g" composer.json
sed -i "s|\"web/app/themes/canopy/src/\"|\"web/app/themes/${PROJECT_SLUG}/src/\"|g" composer.json
sed -i "s|\"agenciaupgrade/canopy\"|\"agenciaupgrade/${PROJECT_SLUG}\"|g" composer.json

# ─── 3. Replace CSS prefix (cnp- → prefix-) ──────────────────────────────────

if [ "$CSS_PREFIX" != "cnp" ]; then
    echo -e "  Replacing CSS prefix ${YELLOW}cnp-${NC} → ${GREEN}${CSS_PREFIX}-${NC}..."
    find "$THEME_DIR" \( -name "*.css" -o -name "*.twig" -o -name "*.php" \) -exec \
        sed -i "s/cnp-/${CSS_PREFIX}-/g" {} +
    find "$THEME_DIR" -name "*.css" -exec \
        sed -i "s/--cnp-/--${CSS_PREFIX}-/g" {} +
fi

# ─── 4. Update style.css metadata ────────────────────────────────────────────

echo -e "  Updating ${YELLOW}style.css${NC} metadata..."
sed -i "s/Theme Name:  Canopy Theme/Theme Name:  ${THEME_NAME}/" "$THEME_DIR/style.css"
sed -i "s|Text Domain: canopy|Text Domain: ${PROJECT_SLUG}|" "$THEME_DIR/style.css"
sed -i "s|Theme URI:   https://github.com/Agencia-Upgrade/canopy|Theme URI:   https://github.com/Agencia-Upgrade/${PROJECT_SLUG}|" "$THEME_DIR/style.css"

# ─── 5. Update text domain in PHP files ──────────────────────────────────────

echo -e "  Updating text domain in PHP files..."
find "$THEME_DIR" -name "*.php" -exec \
    sed -i "s/'canopy'/'${PROJECT_SLUG}'/g" {} +

# ─── 6. Update docker-compose.yml project name ───────────────────────────────

if [ "$ENV_CHOICE" = "1" ] && [ -f "docker-compose.yml" ]; then
    echo -e "  Updating ${YELLOW}docker-compose.yml${NC} project name and database..."
    sed -i "s/^name: canopy$/name: ${PROJECT_SLUG}/" docker-compose.yml
    sed -i "s/MYSQL_DATABASE:      canopy_local/MYSQL_DATABASE:      ${DB_NAME}/" docker-compose.yml
    sed -i "s/MYSQL_USER:          dev/MYSQL_USER:          ${DB_USER}/" docker-compose.yml
    sed -i "s/MYSQL_PASSWORD:      dev/MYSQL_PASSWORD:      ${DB_PASSWORD}/" docker-compose.yml
fi

# ─── 7. Generate .env ────────────────────────────────────────────────────────

echo -e "  Generating ${GREEN}.env${NC}..."

CF_IMAGES_LINE=""
if [ -n "$CF_IMAGES_URL" ]; then
    CF_IMAGES_LINE="CF_IMAGES_URL='${CF_IMAGES_URL}'"
else
    CF_IMAGES_LINE="# CF_IMAGES_URL='https://imagedelivery.net/your-account-hash'"
fi

if [ "$ENV_CHOICE" = "1" ]; then
    SMTP_HOST_LINE="SMTP_HOST=''"
    SMTP_PORT_LINE="SMTP_PORT='1025'"
    SMTP_ENC_LINE="SMTP_ENCRYPTION=''"
else
    SMTP_HOST_LINE="SMTP_HOST=''"
    SMTP_PORT_LINE="SMTP_PORT='465'"
    SMTP_ENC_LINE="SMTP_ENCRYPTION='ssl'"
fi

DB_HOST_LINE="${DB_HOST:-db}"

cat > .env <<EOF
# ${THEME_NAME}
# Agência Upgrade — https://agenciaupgrade.com.br

DB_NAME='${DB_NAME}'
DB_USER='${DB_USER}'
DB_PASSWORD='${DB_PASSWORD}'
DB_HOST='${DB_HOST_LINE}'
# DB_PREFIX='wp_'

WP_ENV='${WP_ENV}'
WP_HOME='${WP_HOME}'
WP_SITEURL="\${WP_HOME}/wp"

# Salts — generated by start.sh
AUTH_KEY='$(LC_ALL=C tr -dc 'A-Za-z0-9!@#$%^&*()_+-=[]{}|' </dev/urandom 2>/dev/null | head -c 64)'
SECURE_AUTH_KEY='$(LC_ALL=C tr -dc 'A-Za-z0-9!@#$%^&*()_+-=[]{}|' </dev/urandom 2>/dev/null | head -c 64)'
LOGGED_IN_KEY='$(LC_ALL=C tr -dc 'A-Za-z0-9!@#$%^&*()_+-=[]{}|' </dev/urandom 2>/dev/null | head -c 64)'
NONCE_KEY='$(LC_ALL=C tr -dc 'A-Za-z0-9!@#$%^&*()_+-=[]{}|' </dev/urandom 2>/dev/null | head -c 64)'
AUTH_SALT='$(LC_ALL=C tr -dc 'A-Za-z0-9!@#$%^&*()_+-=[]{}|' </dev/urandom 2>/dev/null | head -c 64)'
SECURE_AUTH_SALT='$(LC_ALL=C tr -dc 'A-Za-z0-9!@#$%^&*()_+-=[]{}|' </dev/urandom 2>/dev/null | head -c 64)'
LOGGED_IN_SALT='$(LC_ALL=C tr -dc 'A-Za-z0-9!@#$%^&*()_+-=[]{}|' </dev/urandom 2>/dev/null | head -c 64)'
NONCE_SALT='$(LC_ALL=C tr -dc 'A-Za-z0-9!@#$%^&*()_+-=[]{}|' </dev/urandom 2>/dev/null | head -c 64)'

# Redis
WP_REDIS_HOST='redis'
WP_REDIS_PORT='6379'

# Cloudflare Images
${CF_IMAGES_LINE}

# SMTP
${SMTP_HOST_LINE}
${SMTP_PORT_LINE}
${SMTP_ENC_LINE}
SMTP_USER=''
SMTP_PASS=''
SMTP_FROM='noreply@${DOMAIN:-localhost}'
SMTP_FROM_NAME='${THEME_NAME}'
EOF

# ─── 8. Reset git history ────────────────────────────────────────────────────

echo -e "  Resetting Git history (detaching from Canopy origin)..."
rm -rf .git
git init --quiet
git add -A
git commit --quiet -m "Initial commit — ${THEME_NAME} based on Canopy (Agência Upgrade)"
echo -e "  ${GREEN}New Git repo initialized.${NC} Add your remote: ${YELLOW}git remote add origin <url>${NC}"

# ─── 9. Install dependencies ─────────────────────────────────────────────────

echo ""
if [ "$ENV_CHOICE" = "1" ]; then
    echo -e "  ${YELLOW}Skipping composer install${NC} — use Docker:"
    echo -e "  ${YELLOW}make up && make composer install${NC}"
else
    echo -e "  Running ${YELLOW}composer install --no-dev --optimize-autoloader${NC}..."
    composer install --no-dev --optimize-autoloader --no-interaction 2>&1 | tail -5
fi

# ─── Done ─────────────────────────────────────────────────────────────────────

echo ""
echo "────────────────────────────────────────────────"
echo -e "${GREEN}${BOLD}Project configured!${NC}"
echo ""
echo -e "  Theme:   ${GREEN}web/app/themes/${PROJECT_SLUG}/${NC}"
echo -e "  .env:    ${GREEN}.env${NC} (generated)"
echo ""
echo -e "${BOLD}Next steps:${NC}"
echo ""

if [ "$ENV_CHOICE" = "1" ]; then
    echo -e "  1. ${YELLOW}make up${NC}"
    echo -e "  2. ${YELLOW}make composer install${NC}"
    echo -e "  3. ${YELLOW}make wp core install${NC}"
    echo -e "       ${YELLOW}--url=${WP_HOME}${NC}"
    echo -e "       ${YELLOW}--title=\"${THEME_NAME}\"${NC}"
    echo -e "       ${YELLOW}--admin_user=admin${NC}"
    echo -e "       ${YELLOW}--admin_password=password${NC}"
    echo -e "       ${YELLOW}--admin_email=contato@agenciaupgrade.com.br${NC}"
    echo -e "  4. ${YELLOW}make wp theme activate ${PROJECT_SLUG}${NC}"
    echo -e "  5. ${YELLOW}make wp plugin activate redis-cache${NC}"
else
    echo -e "  1. Upload files to ${GREEN}${INSTALL_PATH}${NC}"
    echo -e "  2. Point document root to ${GREEN}${INSTALL_PATH}/web${NC}"
    echo -e "  3. ${YELLOW}wp core install${NC}"
    echo -e "       ${YELLOW}--url=${WP_HOME}${NC}"
    echo -e "       ${YELLOW}--title=\"${THEME_NAME}\"${NC}"
    echo -e "       ${YELLOW}--admin_user=admin${NC}"
    echo -e "       ${YELLOW}--admin_password=password${NC}"
    echo -e "       ${YELLOW}--admin_email=contato@agenciaupgrade.com.br${NC}"
    echo -e "  4. ${YELLOW}wp theme activate ${PROJECT_SLUG}${NC}"
    echo -e "  5. ${YELLOW}wp plugin activate redis-cache${NC}"
fi
echo ""

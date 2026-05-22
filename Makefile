# Canopy — Makefile
# Agência Upgrade — https://agenciaupgrade.com.br

.PHONY: up down build rebuild bash composer wp logs ps setup

up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose build --no-cache php

rebuild: down build up

bash:
	docker compose exec php bash

composer:
	docker compose exec php composer $(filter-out $@,$(MAKECMDGOALS))

wp:
	@docker compose exec php wp --allow-root $(filter-out $@,$(MAKECMDGOALS)) $(ARGS) 2>/dev/null

logs:
	docker compose logs -f

ps:
	docker compose ps

setup:
	@test -f .env || cp .env.example .env
	@echo "Generating salts..."
	@sed -i '' "s|AUTH_KEY='generateme'|AUTH_KEY='$$(openssl rand -base64 48)'|" .env
	@sed -i '' "s|SECURE_AUTH_KEY='generateme'|SECURE_AUTH_KEY='$$(openssl rand -base64 48)'|" .env
	@sed -i '' "s|LOGGED_IN_KEY='generateme'|LOGGED_IN_KEY='$$(openssl rand -base64 48)'|" .env
	@sed -i '' "s|NONCE_KEY='generateme'|NONCE_KEY='$$(openssl rand -base64 48)'|" .env
	@sed -i '' "s|AUTH_SALT='generateme'|AUTH_SALT='$$(openssl rand -base64 48)'|" .env
	@sed -i '' "s|SECURE_AUTH_SALT='generateme'|SECURE_AUTH_SALT='$$(openssl rand -base64 48)'|" .env
	@sed -i '' "s|LOGGED_IN_SALT='generateme'|LOGGED_IN_SALT='$$(openssl rand -base64 48)'|" .env
	@sed -i '' "s|NONCE_SALT='generateme'|NONCE_SALT='$$(openssl rand -base64 48)'|" .env
	docker compose down --remove-orphans 2>/dev/null || true
	docker compose build --no-cache php
	docker compose up -d
	docker compose exec php composer install
	@WP_HOME=$$(grep '^WP_HOME' .env | cut -d= -f2 | tr -d "'\"`"); \
	docker compose exec php wp --allow-root core install \
		--url="$$WP_HOME" \
		--title="Canopy Site" \
		--admin_user=admin \
		--admin_password=admin \
		--admin_email=admin@example.com 2>/dev/null
	docker compose exec php wp --allow-root theme activate canopy 2>/dev/null
	@WP_HOME=$$(grep '^WP_HOME' .env | cut -d= -f2 | tr -d "'\"`"); \
	echo ""; \
	echo "Done! Access $$WP_HOME"; \
	echo "  Admin: $$WP_HOME/wp/wp-admin (admin/admin)"

%:
	@:

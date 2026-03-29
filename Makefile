# Canopy — Makefile
# Agência Upgrade — https://agenciaupgrade.com.br

.PHONY: up down build rebuild bash composer wp logs ps

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
	docker compose exec php wp --allow-root $(filter-out $@,$(MAKECMDGOALS))

logs:
	docker compose logs -f

ps:
	docker compose ps

%:
	@:

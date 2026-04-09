# Makefile for PBL Architecture Project

.PHONY: up down build restart shell logs migrate seed node-install help

help:
	@echo "Usage:"
	@echo "  make up            Start containers in detached mode"
	@echo "  make down          Stop and remove containers"
	@echo "  make build         Build or rebuild containers"
	@echo "  make restart       Stop and start containers"
	@echo "  make shell         Enter the PHP application container"
	@echo "  make logs          Follow logs of all containers"
	@echo "  make migrate       Run database migrations"
	@echo "  make seed          Run database seeders"
	@echo "  make node-install  Run npm install in the node container"

up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose build

restart:
	docker compose down
	docker compose up -d

shell:
	docker exec -it pbl-architecture-app sh

logs:
	docker compose logs -f

migrate:
	docker exec pbl-architecture-app php artisan migrate

seed:
	docker exec pbl-architecture-app php artisan db:seed

node-install:
	docker exec pbl-architecture-node npm install

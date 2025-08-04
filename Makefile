PROJECT_NAME=tinylinks
DOCKER_COMPOSE=docker-compose
PHP_CONTAINER=php

UID := $(shell id -u)
GID := $(shell id -g)

include .env
export $(shell sed 's/=.*//' .env)

up:
	$(DOCKER_COMPOSE) up -d --build

down:
	$(DOCKER_COMPOSE) down

logs:
	$(DOCKER_COMPOSE) logs -f

init:
	docker run --rm --user $(UID):$(GID) -v $(PWD)/app:/app composer create-project --prefer-dist yiisoft/yii2-app-basic /app --no-interaction

composer-install:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) composer install

migrate:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php yii migrate --interactive=0

exec:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) sh

queue-worker:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php yii queue/listen --verbose=1 --isolate=1

status:
	$(DOCKER_COMPOSE) ps

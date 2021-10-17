NAME   := ni_app
COMMIT := $$(git rev-parse --short HEAD)
TAG    := $$(git describe --abbrev=0)
PRE_TAG:= $$(git describe --abbrev=0 --tags `git rev-list --tags --skip=1 --max-count=1`)
IMG    := ${NAME}:${TAG}
LATEST := ${NAME}:latest

THIS_FILE := $(lastword $(MAEFILE_LIST))
.PHONY: help build_app build_router up down tag pre_tag cache restart_redis

help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z1-9_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.DEFAULT_GOAL := help
tag: ## echo the latest git tag
	echo "${TAG}"
setup: ## Hello
	cp ./.env.example .env
	composer install
	./sail npm install
	./sail php artisan key:generate
pre_tag: ## echo the previus git tag
	echo "${PRE_TAG}"
build: ## Build the containers
	docker build --rm -t $(IMG) . -f Dockerfile.production
up: ## Up all services
	TAG=${TAG} docker-compose -f docker-compose.prod.yml up -d
down: ## down the service 1
	TAG=${PRE_TAG} docker-compose -f docker-compose.prod.yml down
cache: ## Remove cache and config
  php artisan cache:clear
  php artisan config:clear

restart_redis: ## Remove redis cache data
  php artisan queue:restart

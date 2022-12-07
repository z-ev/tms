CMD = docker-compose
PM_EXEC := $(CMD) exec php

all: help

help: ## Show this help
	@printf "\033[33m%s:\033[0m\n" 'Available commands'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[32m%-14s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

up: ## Run all services
	$(CMD) up -d

down: ## Stop and remove all services
	$(CMD) down

recreate: ## Restart all services
	$(CMD) down
	$(CMD) up -d

restart: ## Restart all services
	$(CMD) restart

stop: ## Stop all services
	$(CMD) stop

install: ## Install all dependencies
	$(PM_EXEC) /scripts/php.sh

cmd: ## Shell
	$(PM_EXEC) bash

fix: ## Run PHP CodeStyle fix
	$(PM_EXEC) composer run fix

phpstan: ## Run Static Analysis (PHPStan)
	$(PM_EXEC) composer run phpstan

psalm: ## Run Static Analysis (Psalm)
	$(PM_EXEC) composer run psalm

stat: ## Run Static Analysis (Psalm & PHPStan)
	$(PM_EXEC) composer run psalm
	$(PM_EXEC) composer run phpstan

tc: ## Run tests with HTML coverage
	$(PM_EXEC) php -dmemory_limit=-1 -dxdebug.mode=coverage vendor/bin/paratest \
      --configuration=phpunit.xml \
      --runner='\Illuminate\Testing\ParallelRunner' \
      --coverage-html ./public/coverage/ \
      --log-junit ./public/coverage/report.xml \
      --coverage-text

rdb: ## Wipe and migrate db with seeds
	$(PM_EXEC) php ./artisan db:wipe
	$(PM_EXEC) php ./artisan migrate --seed

qw: ## Start queue work
	$(PM_EXEC) php ./artisan queue:work

sr: ## Show routes list
	$(PM_EXEC) php ./artisan route:list

cc: ## Clear cache
	$(PM_EXEC) php ./artisan cache:clear

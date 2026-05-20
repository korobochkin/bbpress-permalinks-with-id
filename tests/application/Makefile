# docker-compose.yml -> name
CONTAINER_NAME_PREFIX := bbpress-permalinks-with-id-application-tests

build:
	docker compose build

build-php:
	docker compose build --quiet --with-dependencies php

up:
	docker compose up \
		--detach \
		--no-build \
		--quiet-pull \
		--remove-orphans \
		--timeout=120 \
		--wait-timeout=120 \
		--wait \
		--yes \
		php-develop

up-php:
	docker compose up \
		--no-build \
		--quiet-pull \
		--remove-orphans \
		--timeout=120 \
		--wait-timeout=120 \
		--yes \
		--abort-on-container-failure \
		--no-log-prefix \
		--exit-code-from=php \
		--attach=php \
		php

stop:
	docker compose stop

down:
	@docker compose down --remove-orphans --volumes

config:
	@docker compose config --quiet

.env:
	@if [ -f .env ]; then \
		echo "File .env exists. Update manually or delete it and rerun the command."; \
	else \
		cp .env.example .env; \
		echo "File created. Do not forget to fill it with values manually."; \
	fi

reset_logs:
	docker exec "$(CONTAINER_NAME_PREFIX)-syslog-1" sh -c "find /var/log/remote -maxdepth 1 -type f -name '*.log' -exec truncate -s 0 {} \;"

vendor: composer.json composer.lock
	composer install --no-scripts --no-interaction --no-progress --classmap-authoritative

run_tests:
	 vendor/bin/phpunit --no-progress --testsuite=default

php_cs_fixer:
	vendor/bin/php-cs-fixer fix --config=php-cs-fixer.php --cache-file=.cache/.php-cs-fixer.cache --format=txt --no-interaction --allow-risky=yes

.PHONY: \
	build \
	build-php \
	up \
	up-php \
	stop \
	down \
	config \
	reset_logs \
	run_tests \
	php_cs_fixer

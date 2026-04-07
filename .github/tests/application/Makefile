build:
	docker compose build --quiet

up:
	docker compose up --detach --no-build --quiet-pull --remove-orphans --timeout=120 --wait-timeout=120 --wait --yes

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
	docker exec syslog sh -c "find /var/log/remote -maxdepth 1 -type f -name '*.log' -exec truncate -s 0 {} \;"

vendor: composer.json composer.lock
	composer install --no-interaction --no-progress --classmap-authoritative

composer_install_from_host:
	docker exec php make vendor

run_tests:
	 vendor/bin/phpunit --no-progress --testsuite=default

run_tests_from_host:
	docker exec php make run_tests

php_cs_fixer:
	vendor/bin/php-cs-fixer fix --config=php-cs-fixer.php --cache-file=.cache/.php-cs-fixer.cache --format=txt --no-interaction

.PHONY: \
	build \
	up \
	down \
	config \
	reset_logs \
	composer_install_from_host \
	run_tests \
	run_tests_from_host \
	php_cs_fixer

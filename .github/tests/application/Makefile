build:
	docker compose build --quiet

up:
	docker compose up --detach --no-build --quiet-pull --remove-orphans --timeout=120 --wait --yes

down:
	@docker compose down --remove-orphans --volumes

config:
	@docker compose config --quiet

composer_install:
	composer install --no-interaction --no-progress

composer_install_from_host:
	docker exec php make composer_install

vendor/bin/phpunit: composer_install

run_tests: vendor/bin/phpunit
	 vendor/bin/phpunit --no-progress --testsuite=default

run_tests_from_host:
	docker exec php make run_tests

php_cs_fixer:
	vendor/bin/php-cs-fixer fix --config=php-cs-fixer.php --cache-file=.cache/.php-cs-fixer.cache --format=txt --no-interaction

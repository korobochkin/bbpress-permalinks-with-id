up:
	@docker compose up --build --detach --quiet-pull --remove-orphans --timeout=120 --wait --yes

down:
	@docker compose down --remove-orphans --volumes

config:
	@docker compose config --quiet

vendor/bin/phpunit:
	composer install --no-interaction --no-progress

run_tests: vendor/bin/phpunit
	 vendor/bin/phpunit --no-progress --testsuite=default

php_cs_fixer:
	vendor/bin/php-cs-fixer fix --config=php-cs-fixer.php --cache-file=.cache/.php-cs-fixer.cache --format=txt --no-interaction

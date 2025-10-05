up:
	@docker compose up --build --detach --quiet-pull --remove-orphans --timeout=120 --wait --yes

down:
	@docker compose down --remove-orphans --volumes

config:
	@docker compose config --quiet

run_tests:
	 vendor/bin/phpunit --no-progress --testsuite=default

include make/common.mk

build:
	docker compose --file docker-compose-tests.yml build --pull

_up-no-start-%:
	docker compose \
		--file=docker-compose-tests.yml \
		up \
		--no-build \
		--quiet-pull \
		--remove-orphans \
		--timeout=120 \
		--wait-timeout=120 \
		--yes \
		--abort-on-container-failure \
		--no-log-prefix \
		--no-start \
		--exit-code-from=$* \
		--attach=$* \
		$*

_up-%:
	docker compose \
		--file=docker-compose-tests.yml \
		up \
		--no-build \
		--quiet-pull \
		--remove-orphans \
		--timeout=120 \
		--wait-timeout=120 \
		--yes \
		--abort-on-container-failure \
		--no-log-prefix \
		--exit-code-from=$* \
		--attach=$* \
		$*

_cp-%: guard-SRC_PATH guard-DEST_PATH
	docker compose \
		--file=docker-compose-tests.yml \
		cp \
		$*:$(SRC_PATH) $(DEST_PATH)

up-php-syntax-check-php-5.6: _up-php-syntax-check-php-5.6

up-php-syntax-check-php-7.4: _up-php-syntax-check-php-7.4

up-php-syntax-check-php-8.0: _up-php-syntax-check-php-8.0

up-php-syntax-check-php-8.1: _up-php-syntax-check-php-8.1

up-php-syntax-check-php-8.2: _up-php-syntax-check-php-8.2

up-php-syntax-check-php-8.3: _up-php-syntax-check-php-8.3

up-php-syntax-check-php-8.4: _up-php-syntax-check-php-8.4

up-php-syntax-check-php-8.5: _up-php-syntax-check-php-8.5

up-php-syntax-check-tests-application: _up-php-syntax-check-tests-application

up-phpcs: _up-phpcs

stop:
	docker compose --file=docker-compose-tests.yml stop

down:
	docker compose --file=docker-compose-tests.yml down --remove-orphans --volumes

.PHONY: \
	build \
	_up-no-start-% \
	_up-% \
	_cp-% \
	up-php-syntax-check-php-5.6 \
	up-php-syntax-check-php-7.4 \
	up-php-syntax-check-php-8.0 \
	up-php-syntax-check-php-8.1 \
	up-php-syntax-check-php-8.2 \
	up-php-syntax-check-php-8.3 \
	up-php-syntax-check-php-8.4 \
	up-php-syntax-check-php-8.5 \
	up-php-syntax-check-tests-application \
	up-phpcs \
	stop \
	down

PHPMD_OUTPUT_TYPE := ansi

phpcs:
	development/phpcs/vendor/bin/phpcs --standard=development/phpcs/phpcs.xml

phpstan:
	development/php-stan/vendor/bin/phpstan analyse --no-interaction --no-progress --configuration=development/php-stan/php-stan.neon

psalm:
	development/psalm/vendor/bin/psalm --config="development/psalm/psalm.xml"

psalm-tests-application:
	development/psalm/vendor/bin/psalm --config="development/psalm/psalm-tests-application.xml"

phpmd:
	development/phpmd/vendor/bin/phpmd \
		plugin.php \
		$(PHPMD_OUTPUT_TYPE) \
		development/phpmd/phpmd.xml \
		-vvv \
		--cache \
		--cache-file=development/phpmd/.cache/.phpmd.result-cache.php

plugin-check:
	@wp plugin check \
		bbpress-permalinks-with-id \
		--exclude-directories='.github,.idea,.wordpress-org,development,tests' \
		--exclude-files='.distignore,.gitattributes,.gitignore' \
		--ignore-codes=trademarked_term \
		--checks=code_obfuscation,file_type,plugin_header_fields,plugin_updater,plugin_uninstall,plugin_readme,localhost,no_unfiltered_uploads,trademarks,offloading_files

php-syntax-check:
	find . \
	\( -path "./.github" -o -path "./development" -o -path "./tests/application" \) -prune \
	-o -type f -name "*.php" -print0 \
	| \
	xargs --null --verbose --max-procs=4 --max-args=1 php --syntax-check

php-syntax-check-tests-application:
	find tests/application \
	\( -path "tests/application/.cache" -o -path "tests/application/vendor" \) -prune \
	-o -type f -name "*.php" -print0 \
	| \
	xargs --null --verbose --max-procs=4 --max-args=1 php --syntax-check

.PHONY: \
	phpcs \
	phpstan \
	psalm \
	psalm-tests-application \
	phpmd \
	plugin-check \
	php-syntax-check \
	php-syntax-check-tests-application

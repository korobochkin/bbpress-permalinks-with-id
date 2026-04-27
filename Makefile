phpcs:
	development/phpcs/vendor/bin/phpcs --standard=development/phpcs/phpcs.xml

phpstan:
	development/php-stan/vendor/bin/phpstan analyse --no-interaction --no-progress --configuration=development/php-stan/php-stan.neon

psalm:
	.github/psalm/vendor/bin/psalm --config=".github/psalm/psalm.xml"

psalm-tests-application:
	.github/psalm/vendor/bin/psalm --config=".github/psalm/psalm-tests-application.xml"

phpmd:
	@.github/phpmd/vendor/bin/phpmd \
		plugin.php \
		ansi \
		.github/phpmd/phpmd.xml \
		-vvv \
		--cache \
		--cache-file=.github/phpmd/.cache/.phpmd.result-cache.php

plugin-check:
	@wp plugin check \
		bbpress-permalinks-with-id \
		--exclude-directories='.github,.idea,.wordpress-org,development' \
		--exclude-files='.distignore,.gitattributes,.gitignore' \
		--ignore-codes=trademarked_term \
		--checks=code_obfuscation,file_type,plugin_header_fields,plugin_updater,plugin_uninstall,plugin_readme,localhost,no_unfiltered_uploads,trademarks,offloading_files

.PHONY: \
	phpcs \
	phpstan \
	psalm \
	psalm-tests-application \
	phpmd \
	plugin-check

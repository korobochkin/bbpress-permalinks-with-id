phpcs:
	.github/phpcs/vendor/bin/phpcs --standard=.github/phpcs/phpcs.xml

phpstan:
	.github/php-stan/vendor/bin/phpstan analyse --configuration=.github/php-stan/php-stan.neon

psalm:
	.github/psalm/vendor/bin/psalm --config=".github/psalm/psalm.xml"

phpmd:
	@.github/phpmd/vendor/bin/phpmd \
		plugin.php \
		ansi \
		.github/phpmd/phpmd.xml \
		-vvv \
		--cache \
		--cache-file=.github/phpmd/.cache/.phpmd.result-cache.php

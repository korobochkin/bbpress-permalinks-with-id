phpcs:
	.github/phpcs/vendor/bin/phpcs --standard=.github/phpcs/phpcs.xml

phpstan:
	.github/php-stan/vendor/bin/phpstan analyse --configuration=.github/php-stan/php-stan.neon

psalm:
	.github/psalm/vendor/bin/psalm --config=".github/psalm/psalm.xml"

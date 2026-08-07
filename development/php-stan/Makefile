WORDPRESS_BRANCH ?= 7.0-branch
BBPRESS_BRANCH ?= 2.6

download-wordpress:
	@if [ ! -d .cache/wordpress ] || [ ! -d .cache/wordpress/.git ]; then \
		echo "Cloning WordPress repository (shallow, latest commit only)..."; \
		git clone --depth 1 --single-branch --branch $(WORDPRESS_BRANCH) https://github.com/WordPress/WordPress.git .cache/wordpress; \
	else \
		echo "WordPress repository exists, fetching updates..."; \
		cd .cache/wordpress && git fetch --depth 1 origin $(WORDPRESS_BRANCH) && git reset --hard FETCH_HEAD; \
	fi

download-bbpress:
	@if [ ! -d .cache/bbpress ] || [ ! -d .cache/bbpress/.git ]; then \
		echo "Cloning bbPress repository (shallow, latest commit only)..."; \
		git clone --depth 1 --single-branch --branch $(BBPRESS_BRANCH) https://github.com/bbpress/bbPress.git .cache/bbpress; \
	else \
		echo "bbPress repository exists, fetching updates..."; \
		cd .cache/bbpress && git fetch --depth 1 origin $(BBPRESS_BRANCH) && git reset --hard FETCH_HEAD; \
	fi

vendor: composer.json composer.lock
	composer install --no-scripts --no-interaction --no-progress --classmap-authoritative

.cache/stubs/wordpress.stub: delete-stub-wordpress
	vendor/bin/generate-stubs --no-interaction .cache/wordpress --out=.cache/stubs/wordpress.stub

.cache/stubs/bbpress.stub: delete-stub-bbpress
	vendor/bin/generate-stubs --no-interaction .cache/bbpress --out=.cache/stubs/bbpress.stub

delete-stub-wordpress:
	@if [ -f .cache/stubs/wordpress.stub ]; then \
  		rm .cache/stubs/wordpress.stub; \
  	fi

delete-stub-bbpress:
	@if [ -f .cache/stubs/bbpress.stub ]; then \
  		rm .cache/stubs/bbpress.stub; \
  	fi

.PHONY: \
	download-wordpress \
	download-bbpress \
	delete-stub-wordpress \
	delete-stub-bbpress

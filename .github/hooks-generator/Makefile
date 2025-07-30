# Default branch to checkout (can be overridden with make download BRANCH=your-branch)
WORDPRESS_BRANCH ?= '6.8-branch'
BBPRESS_BRANCH ?= '2.6'

generate:
	vendor/bin/wp-hooks-generator --input=.cache/wordpress --output=.cache/wordpress-hooks
	vendor/bin/wp-hooks-generator --input=.cache/bbpress --output=.cache/bbpress-hooks

download_wordpress:
	@if [ ! -d .cache/wordpress ] || [ ! -d .cache/wordpress/.git ]; then \
		echo "Cloning WordPress repository (shallow, latest commit only)..."; \
		git clone --depth 1 --single-branch --branch $(WORDPRESS_BRANCH) git@github.com:WordPress/WordPress.git .cache/wordpress; \
	else \
		echo "WordPress repository exists, fetching updates..."; \
		cd .cache/wordpress && git fetch --depth 1 origin $(WORDPRESS_BRANCH) && git reset --hard origin/$(WORDPRESS_BRANCH); \
	fi

download_bbpress:
	@if [ ! -d .cache/bbpress ] || [ ! -d .cache/bbpress/.git ]; then \
		echo "Cloning bbPress repository (shallow, latest commit only)..."; \
		git clone --depth 1 --single-branch --branch $(BBPRESS_BRANCH) git@github.com:bbpress/bbPress.git .cache/bbpress; \
	else \
		echo "bbPress repository exists, fetching updates..."; \
		cd .cache/bbpress && git fetch --depth 1 origin $(BBPRESS_BRANCH) && git reset --hard origin/$(BBPRESS_BRANCH); \
	fi

ARG WORDPRESS_ARG_BASE_IMAGE

FROM alpine:latest AS unzip

RUN apk add --no-cache unzip

FROM unzip AS plugins

ARG WORDPRESS_ARG_PLUGIN_BBPRESS_ZIP_URL

ADD $WORDPRESS_ARG_PLUGIN_BBPRESS_ZIP_URL /tmp/bbpress.zip

RUN unzip -q /tmp/bbpress.zip -d /tmp/plugins

FROM unzip AS themes

ARG WORDPRESS_ARG_THEME_ZIP_URL

ADD $WORDPRESS_ARG_THEME_ZIP_URL /tmp/theme.zip

RUN unzip -q /tmp/theme.zip -d /tmp/themes

FROM $WORDPRESS_ARG_BASE_IMAGE AS wordpress

EXPOSE 8080/tcp

RUN rm -rf /usr/src/wordpress/wp-content/plugins/* \
    && rm -rf /usr/src/wordpress/wp-content/themes/* \
    && rm /usr/src/php*

RUN sed --in-place 's/\*:80>/\*:8080>/g' /etc/apache2/sites-available/* \
	&& \
    sed --in-place 's/^Listen\s80$/Listen 8080/g' /etc/apache2/ports.conf \
    && \
    sed --in-place 's/#ServerName www\.example\.com/ServerName one\.wordpress\.test/g' /etc/apache2/sites-available/*.conf

COPY --chown=www-data:www-data ./mu-plugins /usr/src/wordpress/wp-content/mu-plugins
COPY --from=plugins --chown=www-data:www-data /tmp/plugins /usr/src/wordpress/wp-content/plugins/
COPY --from=themes --chown=www-data:www-data /tmp/themes /usr/src/wordpress/wp-content/themes/

FROM wordpress

ARG NEW_RELIC_AGENT_VERSION
ARG NEW_RELIC_LICENSE_KEY
ARG NEW_RELIC_APPNAME

RUN curl -L https://download.newrelic.com/php_agent/release/newrelic-php5-${NEW_RELIC_AGENT_VERSION}-linux.tar.gz | tar -C /tmp -zx \
    && export NR_INSTALL_USE_CP_NOT_LN=1 \
    && export NR_INSTALL_SILENT=1 \
    && /tmp/newrelic-php5-${NEW_RELIC_AGENT_VERSION}-linux/newrelic-install install \
    && rm -rf /tmp/newrelic-php5-* /tmp/nrinstall*

RUN sed -i \
  -e "s/newrelic.license[[:space:]]*=[[:space:]]*.*/newrelic.license = ${NEW_RELIC_LICENSE_KEY}/" \
  -e "s/newrelic.appname[[:space:]]*=[[:space:]]*.*/newrelic.appname = ${NEW_RELIC_APPNAME}/" \
  -e "\$a newrelic.daemon.address=newrelic-php-daemon:31339" \
  /usr/local/etc/php/conf.d/newrelic.ini

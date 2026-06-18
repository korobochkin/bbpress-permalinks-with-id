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

FROM $WORDPRESS_ARG_BASE_IMAGE

EXPOSE 8080/tcp

SHELL ["/bin/bash", "-euo", "pipefail", "-c"]

RUN rm -rf /usr/src/wordpress/wp-content/plugins/* \
    && rm -rf /usr/src/wordpress/wp-content/themes/* \
    && rm /usr/src/php* \
    && cp --recursive /usr/src/wordpress/* /var/www/html/ \
    && mv /var/www/html/wp-config-docker.php /var/www/html/wp-config.php \
    && chown --recursive www-data:www-data /var/www/html \
    && rm -rf /usr/src/wordpress

RUN sed --in-place 's/\*:80>/\*:8080>/g' /etc/apache2/sites-available/* \
	&& \
    sed --in-place 's/^Listen\s80$/Listen 8080/g' /etc/apache2/ports.conf \
    && \
    sed --in-place 's/#ServerName www\.example\.com/ServerName one\.wordpress\.test/g' /etc/apache2/sites-available/*.conf

COPY --chown=www-data:www-data .htaccess /var/www/html
COPY --chown=www-data:www-data ./mu-plugins /var/www/html/wp-content/mu-plugins
COPY --from=plugins --chown=www-data:www-data /tmp/plugins /var/www/html/wp-content/plugins/
COPY --from=themes --chown=www-data:www-data /tmp/themes /var/www/html/wp-content/themes/

ENTRYPOINT ["apache2-foreground"]

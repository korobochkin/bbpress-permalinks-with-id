FROM wordpress:latest

EXPOSE 8080/tcp

RUN sed --in-place 's/\*:80>/\*:8080>/g' /etc/apache2/sites-available/* \
	&& \
    sed --in-place 's/^Listen\s80$/Listen 8080/g' /etc/apache2/ports.conf \
    && \
    sed --in-place 's/#ServerName www\.example\.com/ServerName one\.wordpress\.test/g' /etc/apache2/sites-available/*.conf

ADD https://downloads.wordpress.org/plugin/bbpress.2.6.0.zip /usr/src/bbpress.zip
ADD https://downloads.wordpress.org/theme/twentytwelve.4.6.zip /usr/src/twentytwelve.zip
RUN apt update --quiet --assume-yes \
    && apt install unzip --quiet --assume-yes --no-install-recommends --no-install-suggests \
    \
	&& rm -rf /usr/src/wordpress/wp-content/plugins/* \
    \
	&& unzip /usr/src/bbpress.zip -d /usr/src/wordpress/wp-content/plugins/ \
	&& unzip /usr/src/twentytwelve.zip -d /usr/src/wordpress/wp-content/themes/ \
    \
    && rm /usr/src/php* \
	&& rm /usr/src/bbpress.zip \
	&& rm /usr/src/twentytwelve.zip

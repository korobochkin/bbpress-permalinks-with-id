FROM wordpress:latest

ADD https://downloads.wordpress.org/plugin/bbpress.2.6.0.zip /usr/src/bbpress.zip
RUN apt update --quiet --assume-yes \
    && apt install unzip --quiet --assume-yes --no-install-recommends --no-install-suggests \
	&& rm -rf /usr/src/wordpress/wp-content/plugins/* \
    && rm /usr/src/php* \
	&& unzip /usr/src/bbpress.zip -d /usr/src/wordpress/wp-content/plugins/

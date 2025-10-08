FROM wordpress:latest

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

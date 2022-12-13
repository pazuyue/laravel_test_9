FROM php:7.3.9-fpm-alpine3.10

RUN apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS \
	&& apk add --no-cache \
			libpng-dev \
			freetype-dev \
			libmcrypt-dev \
			libjpeg-turbo-dev \
			libzip-dev \
			libstdc++ \
	&& pecl install -o -f redis \
	&& rm -rf /tmp/pear \
	&& docker-php-ext-enable redis \
	&& docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
	&& docker-php-ext-install pdo pdo_mysql bcmath gd zip \
	&& apk del .phpize-deps autoconf make gcc g++ perl musl-dev

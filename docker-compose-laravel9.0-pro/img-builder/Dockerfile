FROM php:5.6-fpm

MAINTAINER Nixus <nixus@nixus.cn>

LABEL description="添加redis扩展"

RUN apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS \
    #&& apk add --no-cache ffmpeg \
    && apk add --no-cache \
		    libpng-dev \
		    freetype-dev \
		    libmcrypt-dev \
		    libjpeg-turbo-dev \
		    libzip-dev \
			libstdc++ \
	## 安装图片处理
	#&& apk add --no-cache imagemagick \
	## 编译必备
    && apk add --no-cache autoconf build-base pcre make \
	## 安装swoole依赖
	&& apk add --no-cache openssl-dev \
	## 安装php的redis依赖
    && pecl install -o -f redis \
	#&& pecl install imagick \
    && rm -rf /tmp/pear \
    && apk del .phpize-deps \
    && docker-php-ext-enable redis \
	## 启用图片处理
	#&& docker-php-ext-enable imagick 
	&& docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install pdo pdo_mysql bcmath gd zip \
    && apk del autoconf build-base pcre make gcc g++ musl-dev

# PHP官方镜像
FROM php:8.0.8-fpm-alpine3.13
RUN apt-get update && apt-get install -y wget zip unzip
RUN apt-get install -y cmake libssl-dev
RUN apt-get install -y openssl librabbitmq-dev
#ssh2 扩展
RUN apt-get install -y libssh2-1-dev && pecl install ssh2-1.3.1 && docker-php-ext-enable ssh2
#xlswriter-1.3.7
RUN apt-get install -y zlib1g zlib1g.dev && pecl install xlswriter-1.5.1 && docker-php-ext-enable xlswriter
#signal_handler
RUN wget https://codeload.github.com/rstgroup/php-signal-handler/zip/refs/heads/master -O /tmp/php-signal.zip && cd /tmp && unzip php-signal.zip && docker-php-source extract && mv php-signal-handler-master /usr/src/php/ext/php_signal && docker-php-ext-install -j$(nproc) php_signal
RUN  pecl install amqp-1.11.0 && docker-php-ext-enable amqp && pecl install redis && docker-php-ext-enable redis && docker-php-ext-install pcntl && docker-php-ext-enable pcntl && docker-php-ext-install mysqli&& docker-php-ext-enable mysqli &&docker-php-ext-install pdo_mysql&& docker-php-ext-enable pdo_mysql
RUN wget https://github.com/edenhill/librdkafka/archive/refs/tags/v1.6.2.zip -O /tmp/v1.6.2.zip && cd /tmp && unzip v1.6.2.zip && cd librdkafka-1.6.2 && ./configure && make && make install &&pecl install rdkafka && docker-php-ext-enable rdkafka

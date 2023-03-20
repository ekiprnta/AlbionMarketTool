FROM php:8.1

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update \
    && apt-get install -y -q --no-install-recommends \
        unzip libxml2 libxml2-dev git libmemcached-dev \
        libzip-dev libxslt1.1 libxslt1-dev libjpeg-dev libmagickwand-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libldap2-dev libldap-common \
        librabbitmq-dev \
    && docker-php-ext-install -j$(nproc) ldap iconv gd xml soap pdo_mysql opcache intl pcntl xsl zip pdo_mysql \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && apt-get clean -y \
    && rm -rf /tmp/* /usr/share/doc/* /var/cache/* /var/lib/apt/lists/* /var/tmp/*

# RUN export MAKEFLAGS="-j $(nproc)"  \
#    && pecl -q install imagick && docker-php-ext-enable imagick \
#    && pecl -q install memcached && echo extension=memcached.so >> /usr/local/etc/php/conf.d/memcached.ini \
#    && pecl -q install grpc && echo extension=grpc.so >> /usr/local/etc/php/conf.d/gprc.ini \
#    && printf '\n' | pecl install amqp

# Es ist möglich via shell_exec auch im FPM Container PHP als CLI aufzurufen!
COPY php.ini /usr/local/etc/php/conf.d/98-php_settings.ini


RUN set -xe \
    && curl -OsSL https://getcomposer.org/composer.phar \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer
RUN apt-get update \
    && apt-get install -y --no-install-recommends ssh \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

COPY php.ini /usr/local/etc/php/conf.d/99-php_settings.ini

RUN apt-get install -y locales && \
  sed -i 's/^# *\(en_US.UTF-8\)/\1/' /etc/locale.gen && \
  locale-gen en_US.utf8

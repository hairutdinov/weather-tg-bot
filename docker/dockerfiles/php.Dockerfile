FROM php:7.4.33-fpm

ARG INSTALL_XDEBUG=false

# Install required system packages for PHP extensions for Yii 2.0 Framework
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
        intl \
        amqp \
        http

RUN export DEBIAN_FRONTEND=noninteractive \
    && apt-get update \
    && apt-get install -qq -y \
      curl \
      wget \
      git \
      zip unzip \
      libzip-dev \
      libmcrypt-dev \
      libmemcached-dev \
      libpq-dev \
      zlib1g-dev libpng-dev libjpeg-dev \
    && pecl install mcrypt-1.0.7 \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-install -j$(nproc) \
        mysqli \
        pdo pdo_mysql \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && docker-php-source delete \
    && apt-get remove -y g++ wget \
    && apt-get autoremove --purge -y && apt-get autoclean -y && apt-get clean -y \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/* /var/tmp/*

RUN docker-php-ext-install bcmath sockets \
    && pecl install memcached \
    && docker-php-ext-enable memcached

RUN pecl install xdebug-3.1.5 \
    && docker-php-ext-enable xdebug

#RUN apt-get update && apt-get install -y \
#    libmagickwand-dev --no-install-recommends \
#    && pecl install imagick \
#    && docker-php-ext-enable imagick

RUN apt-get update && apt-get install -y \
    libgraphicsmagick1-dev graphicsmagick --no-install-recommends \
    && pecl install gmagick-2.0.6RC1 \
    && docker-php-ext-enable gmagick

RUN docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Конфигурация Xdebug
RUN if [ ${INSTALL_XDEBUG} = true ]; \
    then \
        docker-php-ext-enable xdebug && \
        echo "xdebug.start_with_request = yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
        echo "xdebug.idekey = PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
        echo "xdebug.max_nesting_level = 1000" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
        echo "xdebug.client_host = host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
        echo "xdebug.client_port = 9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
        echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    fi;

WORKDIR /app

CMD ["php-fpm"]
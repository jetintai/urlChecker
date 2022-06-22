FROM php:7.4-fpm

RUN echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list
RUN apt-get update && apt-get install -y \
        git \
        zip \
        unzip \
        libicu-dev \
        libbz2-dev \
        libpng-dev \
        libjpeg-dev \
        libmcrypt-dev \
        libreadline-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
        libonig-dev \
        supervisor \
        freetype* \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install zip \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install sockets \
    && docker-php-source delete

RUN  pecl install xdebug \
        && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -fsSL https://deb.nodesource.com/setup_12.x | bash -
RUN apt-get install -y symfony-cli

COPY ./php.ini /usr/local/etc/php/php.ini
WORKDIR /code/app

RUN usermod -u 1000 www-data
RUN chown -R www-data: /code

RUN mkdir /var/www/.composer/
RUN chown -R www-data: /var/www/.composer/


USER www-data

RUN rm -rf /var/cache/apk/*

COPY ./yii-director-process.conf /etc/supervisor/conf.d/yii-director-process.conf
COPY ./yii-check-process.conf /etc/supervisor/conf.d/yii-check-process.conf
COPY ./yii-check-process.conf /etc/supervisor/conf.d/yii-check-process.conf
COPY ./php-fpm.conf /etc/supervisor/conf.d/php-fpm.conf
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN rm -rf /code/app/console/log/*
WORKDIR "/code/app"

CMD ["php-fpm", "--nodaemonize"]
#CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]


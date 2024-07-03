FROM php:8.3.4-fpm-bullseye

# 開発環境
ARG ENVIRONMENT

# env
ENV ENVIRONMENT ${ENVIRONMENT}
ENV AWS_REGION ap-northeast-1

# env(setup composer)
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin

# add nginx User
RUN groupadd -g 1000 sample2024 && \
    useradd -m -s /bin/bash -u 1000 -g 1000 sample2024

# install
RUN apt-get update \
    && apt-get install -y zlib1g-dev mariadb-client vim libzip-dev libpng-dev unzip procps \
    && docker-php-ext-install zip pdo_mysql gd \
    && pecl install mailparse \
    && docker-php-ext-enable mailparse

RUN docker-php-ext-configure pcntl --enable-pcntl \
  && docker-php-ext-install \
    pcntl

# Install XDebug
RUN if [ "$ENVIRONMENT" = "local" ]; then pecl install xdebug && docker-php-ext-enable xdebug; fi

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Laravel
ADD ./api /var/www/html
ADD ./docker/api/entrypoint /app
# unix socket
RUN mkdir /var/run/php-fpm
VOLUME ["/var/run/php-fpm"]
# fpm-conf
ADD ./docker/api/fpm/${ENVIRONMENT}/zzz-docker.conf /usr/local/etc/php-fpm.d/zzz-docker.conf
# copy
COPY ./docker/api/php.ini /usr/local/etc/php/

# set timezone
RUN cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime

RUN chmod -R 775 /var/www/html/storage
RUN chown -R sample2024:sample2024 /var/www/html

# change api dir
WORKDIR /var/www/html

# Install Laravel
RUN composer global require "laravel/installer"
RUN if [ "$ENVIRONMENT" = "local" ] ; then composer install; else composer install --optimize-autoloader --no-dev; fi

# set AWS_CONTAINER_CREDENTIALS_RELATIVE_URI
RUN echo 'export $(strings /proc/1/environ | grep AWS_CONTAINER_CREDENTIALS_RELATIVE_URI)' >> /etc/bashrc

# start server
RUN chmod +x /app/entrypoint-${ENVIRONMENT}.sh
CMD /app/entrypoint-${ENVIRONMENT}.sh && tail -f /dev/null

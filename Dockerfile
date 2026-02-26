FROM ubuntu:latest

# Set non-interactive mode for apt
ARG DEBIAN_FRONTEND=noninteractive

# Update and install required packages in a single RUN command
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y \
        apt-utils \
        locales \
        software-properties-common \
        nginx \
        sqlite3 \
        curl \
        git

RUN add-apt-repository ppa:ondrej/php
RUN apt-get update && \
    apt-get install -y \
    php8.4 \
    php8.4-fpm \
    php8.4-xml \
    php8.4-mbstring \
    php8.4-curl \
    php8.4-gmp \
    php8.4-gd \
    php8.4-sqlite3 \
    php8.4-bcmath \
    php8.4-zip

RUN apt-get clean


# Set the locale
RUN locale-gen fr_FR.UTF-8
ENV LC_ALL=fr_FR.UTF-8

# Set working directory
WORKDIR /var/www/USMCalculator

# Copy application files
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --optimize-autoloader && \
    bin/console cache:clear && \
    bin/console doctrine:schema:drop --force && \
    bin/console doctrine:schema:create

# Configure Nginx
RUN rm /etc/nginx/sites-enabled/default && \
    cp nginx/vhost.conf /etc/nginx/sites-available/ && \
    ln -s /etc/nginx/sites-available/vhost.conf /etc/nginx/sites-enabled/vhost.conf

# Set permissions
RUN chmod -R 777 var/

# Copy and setup entrypoint
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Define default command
CMD ["/usr/local/bin/docker-entrypoint.sh"]

# Expose ports
EXPOSE 80

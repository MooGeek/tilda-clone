FROM php:7.3-apache-stretch

# Install & configure supervisord

RUN apt-get update && apt-get install -y \
    supervisor \
    && rm -rf /var/lib/apt/lists/*

COPY .docker/supervisord/supervisord.conf /etc/supervisord.conf

# Copy application

RUN mkdir /app
WORKDIR /app

COPY ./ /app

# Remove default apache configuration and copy our config

RUN rm -rf /etc/apache2/sites-*/*.conf

COPY .docker/apache/config/ /etc/apache2/sites-available/

RUN ln -s /etc/apache2/sites-available/* /etc/apache2/sites-enabled/

# Enable mod-rewrite

RUN a2enmod rewrite

EXPOSE 80

CMD ["supervisord", "--nodaemon", "--configuration", "/etc/supervisord.conf"]
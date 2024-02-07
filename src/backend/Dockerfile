FROM php:7.4-apache AS builder

RUN a2enmod rewrite
RUN service apache2 restart
RUN docker-php-ext-install pdo pdo_mysql

FROM php:7.4-apache

WORKDIR /var/www/html

COPY --from=builder /usr/local /usr/local
COPY . .
RUN a2enmod rewrite
EXPOSE 80

CMD ["apache2-foreground"]

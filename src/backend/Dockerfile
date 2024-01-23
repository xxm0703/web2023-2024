FROM php:7.4-apache

WORKDIR /var/www/html

COPY . .

RUN a2enmod rewrite
RUN service apache2 restart
RUN docker-php-ext-install pdo pdo_mysql

EXPOSE 80

CMD ["apache2-foreground"]

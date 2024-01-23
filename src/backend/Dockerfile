# Use the official PHP and Apache image
FROM php:7.4-apache

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Copy the local code into the container at /var/www/html
COPY . .

# Enable Apache modules and configurations for rewriting URLs
RUN a2enmod rewrite
RUN service apache2 restart
RUN docker-php-ext-install pdo pdo_mysql
# Install any dependencies your PHP code may require
# For example, if you're using Composer, uncomment the following lines
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# RUN composer install --no-interaction --no-dev --optimize-autoloader

# Expose port 80 for Apache
EXPOSE 80

# Define the command to run on container start
CMD ["apache2-foreground"]

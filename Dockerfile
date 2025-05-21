FROM php:8.2-apache

# Включаем модуль rewrite для .htaccess
RUN a2enmod rewrite

# Добавляем директиву ServerName в apache2.conf
RUN echo "ServerName banner" >> /etc/apache2/apache2.conf

# Копируем конфиг Apache
COPY apache-vhost.conf /etc/apache2/sites-available/000-default.conf

# Копируем php.ini
COPY php-custom.ini /usr/local/etc/php/conf.d/custom.ini

# Устанавливаем расширения PHP (если нужно)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Перезапускаем Apache
CMD ["apache2-foreground"]

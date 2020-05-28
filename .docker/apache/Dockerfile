FROM php:7.4-apache

RUN apt-get update
RUN a2enmod alias proxy proxy_fcgi rewrite headers
COPY ./sites-enabled /etc/apache2/sites-enabled
RUN chown -R www-data:www-data /var/www/html
RUN service apache2 restart

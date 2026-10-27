# Usamos PHP 8.2 con Apache
FROM php:8.2-apache

# Instalamos las extensiones necesarias para MySQL (mysqli y pdo)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copiamos todo el proyecto a la carpeta pública de Apache
COPY . /var/www/html/

# Damos permisos a la carpeta
RUN chown -R www-data:www-data /var/www/html

# Exponemos el puerto 80 (Render lo detecta automáticamente)
EXPOSE 80
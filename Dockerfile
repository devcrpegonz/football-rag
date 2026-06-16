# Imagen base: PHP 8.3 en modo CLI (sin servidor web, solo intérprete)
FROM php:8.4-cli

# Directorio de trabajo dentro del contenedor
WORKDIR /var/www/html

# Instala las dependencias necesarias para pdo_pgsql
RUN apt-get update && apt-get install -y libpq-dev

# Instala la extensión de PHP para conectarse a PostgreSQL
RUN docker-php-ext-install pdo_pgsql

# Copia el binario de Composer desde su imagen oficial al contenedor
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia el código de Laravel al directorio de trabajo
COPY ./app /var/www/html

# Instala las dependencias de PHP del proyecto
RUN composer install
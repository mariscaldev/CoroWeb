# Imagen oficial de PHP con Composer y Node preinstalados (pública y mantenida)
FROM richarvey/nginx-php-fpm:php8.2

# Setea el working dir
WORKDIR /var/www/html

# Copia los archivos del proyecto
COPY . .

# Instala Composer si no viene incluido (opcional)
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Instala dependencias
RUN composer install --no-interaction --prefer-dist --optimize-autoloader && \
    curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    npm install && \
    npm run build

# Expone el puerto que Laravel usará
EXPOSE 10000

# Comando para iniciar Laravel
CMD php artisan serve --host=0.0.0.0 --port=10000

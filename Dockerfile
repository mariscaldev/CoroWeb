# Usa una imagen base con PHP, Composer, Node.js y npm
FROM laravelsail/php83-composer-node

# Crea y usa el directorio de la app
WORKDIR /var/www/html

# Copia los archivos del proyecto
COPY . .

# Instala dependencias PHP y Node
RUN composer install --no-interaction --prefer-dist --optimize-autoloader && \
    npm install && \
    npm run build

# Expone el puerto que Laravel usará
EXPOSE 10000

# Comando para iniciar Laravel
CMD php artisan serve --host=0.0.0.0 --port=10000

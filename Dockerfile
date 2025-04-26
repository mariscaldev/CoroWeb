# Etapa 1: Node + Composer para build y dependencias
FROM node:18 as build-stage

WORKDIR /app

# Instala PHP y herramientas necesarias
RUN apt-get update && \
    apt-get install -y php php-cli php-mbstring php-xml php-curl php-pgsql unzip curl git && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copia código y archivos necesarios
COPY . .

# Instala dependencias
RUN composer install --no-dev --optimize-autoloader && \
    npm install && npm run build

# Etapa 2: Imagen final solo con PHP y dependencias de Laravel
FROM php:8.3-cli

WORKDIR /app

# Instala extensiones necesarias
RUN apt-get update && apt-get install -y libpq-dev unzip git && docker-php-ext-install pdo pdo_pgsql

# Copia desde la etapa de build
COPY --from=build-stage /app /app

EXPOSE 8000

CMD php artisan config:cache && php artisan serve --host=0.0.0.0 --port=8000

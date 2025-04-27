# Etapa 1: build con Node y Composer
FROM node:18 as build-stage

WORKDIR /app

# Instala PHP + dependencias de sistema
RUN apt-get update && \
    apt-get install -y php php-cli php-mbstring php-xml php-curl php-pgsql unzip curl git && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copia todo el código
COPY . .

# Instala dependencias PHP y JS + compila assets
RUN composer install --no-dev --optimize-autoloader && \
    npm install && npm run build

# Etapa 2: imagen final de producción
FROM php:8.3-cli

WORKDIR /app

# Instala extensiones necesarias
RUN apt-get update && apt-get install -y libpq-dev unzip git && docker-php-ext-install pdo pdo_pgsql

# Copia todo el proyecto desde build-stage
COPY --from=build-stage /app /app

EXPOSE 8000

# Comando para iniciar Laravel en puerto público
CMD php -S 0.0.0.0:8000 -t public

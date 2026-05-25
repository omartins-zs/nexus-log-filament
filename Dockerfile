FROM php:8.4-fpm

# Instalar dependências básicas de sistema rápido sem instalar pacotes desnecessários
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    curl \
    mariadb-client \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP em um único layer
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache

# Instalar Redis extension usando o PECL
RUN pecl install redis && docker-php-ext-enable redis

# Configurar Opcache e FPM otimizados a partir da estrutura
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini
COPY docker/php/fpm-performance.conf /usr/local/etc/php-fpm.d/zz-fpm-performance.conf

# Preparar o script de startup
COPY docker/scripts/start-app.sh /usr/local/bin/start-app
RUN chmod +x /usr/local/bin/start-app

WORKDIR /var/www

# Inicia o app através do nosso script inteligente
CMD ["start-app"]

FROM php:8.2-cli

# Dependências de sistema + extensões PHP para Laravel + PostgreSQL
RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip mbstring bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Node.js 20 (Vite 6+ requer Node 20.19+)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 8000 5173

# Sobe Vite em background e Laravel na frente
CMD ["sh", "-c", "if [ -f artisan ]; then npm install && npm run dev -- --host 0.0.0.0 & php artisan serve --host=0.0.0.0 --port=8000; else echo 'Aguardando instalacao do Laravel...' && tail -f /dev/null; fi"]

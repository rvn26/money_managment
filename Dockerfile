FROM php:8.4-fpm-alpine

WORKDIR /var/www/html

# 1. Install system dependencies (Ditambah oniguruma-dev dan curl)
RUN apk add --no-cache \
    nginx \
    supervisor \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libwebp-dev \
    oniguruma-dev \
    curl \
    zip \
    unzip \
    git \
    nodejs \
    npm

# 2. Install PHP extensions (Dipercepat dengan -j$(nproc) dan ditambah mbstring, exif, pcntl, bcmath)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) pdo pdo_pgsql pdo_mysql zip opcache gd mbstring exif pcntl bcmath

# 3. Setup PHP Production & OPcache Settings (Diambil dari Trixie)
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
    echo "opcache.enable=1" >> "$PHP_INI_DIR/conf.d/opcache.ini" && \
    echo "opcache.memory_consumption=256" >> "$PHP_INI_DIR/conf.d/opcache.ini" && \
    echo "opcache.interned_strings_buffer=16" >> "$PHP_INI_DIR/conf.d/opcache.ini" && \
    echo "opcache.max_accelerated_files=10000" >> "$PHP_INI_DIR/conf.d/opcache.ini" && \
    echo "opcache.validate_timestamps=0" >> "$PHP_INI_DIR/conf.d/opcache.ini"

# 4. Nginx Configuration Heredoc (Disesuaikan untuk path Alpine)
RUN rm /etc/nginx/http.d/default.conf || true
RUN cat << 'EOF' > /etc/nginx/http.d/laravel.conf
server {
    listen 80;
    listen [::]:80;
    server_name _;
    root /var/www/html/public;
    index index.php index.html;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

# 5. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files first (better caching)
COPY composer.json composer.lock ./

ENV COMPOSER_IP_RESOLVE=v4
RUN composer install --no-dev --no-scripts --no-autoloader --ignore-platform-reqs --no-interaction --prefer-dist

# Copy package files for npm caching
COPY package.json package-lock.json ./
RUN npm ci

# 6. Copy application dengan langsung memberikan ownership
COPY --chown=www-data:www-data . .

# Generate autoloader
RUN composer dump-autoload --optimize \
    && composer clear-cache

# 7. Build frontend assets & Cleanup Node/NPM untuk menghemat ukuran image (Diambil dari Trixie)
RUN npm run build \
    && rm -rf node_modules \
    && apk del nodejs npm

# 8. Run artisan optimize commands (Hanya jalankan jika kamu tidak pakai .env khusus saat runtime)
# PENTING: Jika menggunakan env saat runtime, pindahkan cache ini ke dalam entrypoint/CMD
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan event:cache

# Set specific permissions for storage and bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy supervisord config
COPY docker/supervisord.conf /etc/supervisord.conf

# Cleanup composer (Opsional)
RUN rm /usr/bin/composer

EXPOSE 80

# 9. CMD disederhanakan. Jangan jalankan config:clear dan cache:clear jika sudah dicache di tahap build.
CMD ["sh", "-c", "/usr/bin/supervisord -c /etc/supervisord.conf"]

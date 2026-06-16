FROM webdevops/php-nginx:alpine-php8.2

ENV WEB_DOCUMENT_ROOT=/app/public
ENV APP_ENV=production

WORKDIR /app
COPY . .

RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN chown -R application:applicatiion /app
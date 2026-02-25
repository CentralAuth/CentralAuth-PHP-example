FROM php:8.3-fpm-alpine

RUN apk add --no-cache nginx supervisor

RUN mkdir -p /run/nginx

WORKDIR /var/www/html

COPY . /var/www/html

COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisord.conf

RUN chown -R www-data:www-data /var/www/html

EXPOSE 8080

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
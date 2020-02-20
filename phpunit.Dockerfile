### PHP version we are targetting
ARG PHP_VERSION=7.2

FROM uogsoe/soe-php-apache:${PHP_VERSION} as prod

WORKDIR /var/www/html

USER nobody

ENV APP_ENV=testing
ENV APP_DEBUG=1

CMD ["./vendor/bin/phpunit", "--testdox", "--stop-on-defect"]


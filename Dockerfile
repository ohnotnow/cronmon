### PHP version we are targetting
ARG PHP_VERSION=7.2

### Build JS/css assets
FROM node:10 as frontend

USER node
WORKDIR /home/node

RUN mkdir -p /home/node/public/css /home/node/public/js /home/node/resources

USER root
# workaround for mix.version() webpack bug
RUN ln -s /home/node/public /public
USER node

COPY --chown=node:node package*.json webpack.mix.js tailwind.js /home/node/
COPY --chown=node:node resources/ /home/node/resources/

RUN npm install && \
    npm run production && \
    npm cache clean --force

### And build the prod app
FROM uogsoe/soe-php-apache:${PHP_VERSION} as prod

WORKDIR /var/www/html

ENV APP_ENV=production
ENV APP_DEBUG=0

#- Copy our start scripts and php/ldap configs in
COPY docker/ldap.conf /etc/ldap/ldap.conf
COPY docker/custom_php.ini /usr/local/etc/php/conf.d/custom_php.ini
COPY docker/app-start docker/app-healthcheck /usr/local/bin/
RUN chmod u+x /usr/local/bin/app-start /usr/local/bin/app-healthcheck

#- Copy in our code
COPY . /var/www/html

#- Symlink the docker secret to the local .env so Laravel can see it
RUN ln -sf /run/secrets/.env /var/www/html/.env

#- install our php dep's
USER nobody
RUN composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --no-dev \
    --prefer-dist
USER root

#- Copy in our front-end assets
COPY --from=frontend /home/node/public/js /var/www/html/public/js
COPY --from=frontend /home/node/public/css /var/www/html/public/css
COPY --from=frontend /home/node/mix-manifest.json /var/www/html/mix-manifest.json

#- Clean up and production-cache our apps settings/views/routing
RUN rm -fr /var/www/html/bootstrap/cache/*.php && \
    chown -R www-data:www-data storage bootstrap/cache && \
    php /var/www/html/artisan storage:link && \
    php /var/www/html/artisan view:cache && \
    php /var/www/html/artisan route:cache

#- Set up the default healthcheck
HEALTHCHECK --start-period=30s CMD /usr/local/bin/app-healthcheck

#- And off we go...
CMD ["/usr/local/bin/app-start"]

### Build the ci version of the app (prod+dev packages)
FROM prod as ci

ENV APP_ENV=local
ENV APP_DEBUG=1

#- Install our remaining dev dependencies
USER nobody
RUN composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist
USER root

#- Install sensiolabs security scanner and clear the caches
RUN curl -o /usr/local/bin/security-checker https://get.sensiolabs.org/security-checker.phar && \
    php /var/www/html/artisan view:clear && \
    php /var/www/html/artisan cache:clear


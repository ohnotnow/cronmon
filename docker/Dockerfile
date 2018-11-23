FROM uogsoe/soe-php-apache:7.2

WORKDIR /var/www/html

COPY docker/start.sh /usr/local/bin/start
RUN chmod u+x /usr/local/bin/start

COPY docker/ldap.conf /etc/ldap/ldap.conf
COPY docker/install_composer.sh /tmp
RUN chmod +x /tmp/install_composer.sh

COPY . /var/www/html

RUN /tmp/install_composer.sh
RUN /usr/local/bin/php composer.phar install --no-dev
RUN /usr/local/bin/php artisan key:generate
RUN /usr/local/bin/php artisan view:clear
RUN /usr/local/bin/php artisan config:cache

RUN chown -R www-data:www-data /var/www/html

CMD ["/usr/local/bin/start"]

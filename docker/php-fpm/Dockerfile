FROM beeis/woodcard-php-cli

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -yqq --allow-unauthenticated install php7.1-fpm \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Increase php post_max_size and upload_max_filesize to 128M
RUN sed -i "/post_max_size = .*/c\post_max_size = 80M" /etc/php/7.1/fpm/php.ini \
    && sed -i "/upload_max_filesize = .*/c\upload_max_filesize = 90M" /etc/php/7.1/fpm/php.ini

# Configure FPM to run properly on docker
RUN sed -i "/listen = .*/c\listen = [::]:9000" /etc/php/7.1/fpm/pool.d/www.conf \
    && sed -i "/user = .*/c\user = syswood" /etc/php/7.1/fpm/pool.d/www.conf \
    && sed -i "/group = .*/c\group = syswood" /etc/php/7.1/fpm/pool.d/www.conf \
    && sed -i "/;access.log = .*/c\access.log = /proc/self/fd/2" /etc/php/7.1/fpm/pool.d/www.conf \
    && sed -i "/;clear_env = .*/c\clear_env = no" /etc/php/7.1/fpm/pool.d/www.conf \
    && sed -i "/;catch_workers_output = .*/c\catch_workers_output = yes" /etc/php/7.1/fpm/pool.d/www.conf \
    && sed -i "/pid = .*/c\;pid = /run/php/php7.1-fpm.pid" /etc/php/7.1/fpm/php-fpm.conf \
    && sed -i "/;daemonize = .*/c\daemonize = no" /etc/php/7.1/fpm/php-fpm.conf \
    && sed -i "/;pm.max_requests = .*/c\pm.max_requests = 500" /etc/php/7.1/fpm/php-fpm.conf \
    && sed -i "/error_log = .*/c\error_log = /proc/self/fd/2" /etc/php/7.1/fpm/php-fpm.conf \
    && usermod -u 1000 syswood

# The following runs FPM and removes all its extraneous log output on top of what your app outputs to stdout
CMD /usr/sbin/php-fpm7.1 -F -O 2>&1 | sed -u 's,.*: \"\(.*\)$,\1,'| sed -u 's,"$,,' 1>&1

WORKDIR /var/www/woodcard

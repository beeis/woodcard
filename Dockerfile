FROM beeis/woodcard-base

RUN apt-get update

WORKDIR /var/www/woodcard

COPY . /var/www/woodcard

RUN chown -R syswood:syswood /var/www/woodcard

RUN chmod -R 0777 /var/www/woodcard/var

VOLUME /var/www/woodcard

ENTRYPOINT ["/usr/bin/tail", "-f", "/dev/null"]

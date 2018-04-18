FROM ubuntu:16.04

RUN apt-get update

WORKDIR /var/www/gts-app

COPY . /var/www/gts-app

#RUN chown -R sysccg:sysccg /var/www/ccg-app

RUN chmod -R 0777 /var/www/gts-app/var

VOLUME /var/www/gts-app

ENTRYPOINT ["/usr/bin/tail", "-f", "/dev/null"]

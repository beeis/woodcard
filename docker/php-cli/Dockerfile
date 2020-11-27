FROM beeis/woodcard-base

# Install selected extensions and other stuff
RUN add-apt-repository -y ppa:ondrej/php \
    && apt-get update \
    && apt-get -yqq --allow-unauthenticated install php7.4-cli php7.4-curl php7.4-zip php7.4-xml php7.4-mysql php7.4-redis php7.4-gd php7.4-intl php7.4-mbstring php7.4-bcmath php7.4-xsl php7.4-imagick \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

CMD ["php", "-a"]


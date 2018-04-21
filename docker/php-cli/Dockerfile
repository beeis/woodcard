FROM beeis/woodcard-base

# Install selected extensions and other stuff
RUN add-apt-repository -y ppa:ondrej/php \
    && apt-get update \
    && apt-get -yqq --allow-unauthenticated install php7.1-cli php7.1-curl php7.1-zip php7.1-xml php7.1-mysql php7.1-redis php7.1-gd php7.1-intl php7.1-mbstring php7.1-bcmath php7.1-xsl php7.1-imagick \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

CMD ["php", "-a"]


FROM ubuntu:16.04

# Fixes some weird terminal issues such as broken clear / CTRL+L
ENV TERM=linux
ENV LC_ALL=C.UTF-8

# Install cURL and nano
RUN apt-get update \
    && apt-get install -y --no-install-recommends curl ca-certificates nano vim htop mc python-software-properties software-properties-common \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN adduser --system syswood --group

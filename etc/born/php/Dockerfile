FROM ubuntu:bionic
WORKDIR /usr/lifeeka-user

ENV TZ=Asia/Colombo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update -y
RUN apt-get install -y systemd build-essential
RUN apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv EA312927

#install PHP
RUN apt install -y software-properties-common
RUN add-apt-repository -y ppa:ondrej/php
RUN apt-get update -y

RUN apt-get install -y php7.3-fpm
RUN apt-get install -y php7.3-common php7.3-mysql php7.3-xml php7.3-xmlrpc php7.3-curl php7.3-gd php7.3-imagick php7.3-cli php7.3-dev php7.3-imap php7.3-mbstring

#install Nginx
RUN mkdir -p /run/php/

RUN apt-get install nginx -y
COPY nginx.conf /etc/nginx/sites-enabled/default

ENTRYPOINT sh docker-srartup.sh

EXPOSE 80 3306

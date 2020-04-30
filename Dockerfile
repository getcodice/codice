FROM greyltc/archlinux
MAINTAINER Maciej Sobaczewski <msobaczewski@gmail.com>

WORKDIR /usr/local/src

RUN pacman -Syu --noconfirm php php-mcrypt php-intl nginx php-fpm php-gd php-sqlite composer nodejs npm
RUN npm i -g yarn

COPY / /usr/local/src

RUN yarn install
RUN composer install

RUN node_modules/.bin/gulp assets
CMD ["php", "artisan", "serve", "--host=0.0.0.0"]

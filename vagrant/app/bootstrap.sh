#!/usr/bin/env bash

sudo apt-get update
sudo apt-get install -y php-cli php-curl git curl zip  phpunit

mkdir -p /bin
cd /bin
wget -q https://getcomposer.org/composer.phar

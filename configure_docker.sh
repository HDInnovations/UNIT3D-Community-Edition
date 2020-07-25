#!/bin/bash
hostnameValid=false
emailValid=false
hostname=""
email="$2"

if [ "$1" != "" ]; then
  hostnameValid=true
  hostname=$1
fi

if [ "$2" != "" ]; then
  emailValid=true
  email=$2
fi
while [ $hostnameValid == false ]; do
  read -rp "Enter your hostname (eg: example.com): " hostname
  echo "Using hostname for generated configs: $hostname"
  read -rp "Is this correct? [Y/n]" correct
  if [ "$correct" == "" ]; then
    hostnameValid=true
  fi
done

while [ $emailValid == false ]; do
  read -rp "Enter your email for Lets Encrypt SSL Certs (eg: user@host.com): " email
  echo "Using email for registration: $email"
  read -rp "Is this correct? [Y/n]" correctE
  if [ "$correctE" == "" ]; then
    emailValid=true
  fi
done


echo "Generating configs for: ${email} @ https://${hostname}"
cp docker/template/nginx.conf docker/nginx.conf
sed -i -r "s/APP_HOSTNAME/${hostname}/g" docker/nginx.conf

cp docker/template/.env.example docker/.env
sed -i -r "s/APP_URL=$/APP_URL=https:\/\/${hostname}/g" docker/.env
sed -i -r "s/APP_HOSTNAME=$/APP_HOSTNAME=${hostname}/g" docker/.env

cp docker/template/Caddyfile docker/Caddyfile
sed -i -r "s/APP_HOSTNAME/${hostname}/g" docker/Caddyfile
sed -i -r "s/APP_EMAIL/${email}/g" docker/Caddyfile
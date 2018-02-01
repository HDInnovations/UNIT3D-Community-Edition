#!/bin/bash
# UNIT3D MySQL package for Ubuntu 16.04

# Am I root?
if [ "x$(id -u)" != 'x0' ]; then
    echo
    echo "================ Error ================="
    echo "This script can only be executed by root"
    echo "========================================"
    echo
    exit 1


# Check OS
if [ "$(head -n1 /etc/issue | cut -f 1 -d ' ')" != 'Ubuntu' ] && [ "$(lsb_release -r|awk '{print $2}')" != '16.04' ]; then
    echo
    echo "================== Error ====================="
    echo "This script may be run only on Ubuntu 16.04"
    echo "=============================================="
    echo
    exit 1
fi

gen_pass() {
    MATRIX='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'
    LENGTH=16
    while [ ${n:=1} -le $LENGTH ]; do
        PASS="$PASS${MATRIX:$(($RANDOM%${#MATRIX})):1}"
        let n+=1
    done
    echo "$PASS"
}

clear
echo
echo
echo '  ██   ██  ███     ██  ██  ████████  ██████  ██████   ███████     '
echo '  ██   ██  ██ ██   ██  ██     ██     ██           ██  ██     ██   '
echo '  ██   ██  ██  ██  ██  ██     ██     ██████   ██║██   ██      ██  '
echo '  ██   ██  ██   ██ ██  ██     ██     ██           ██  ██     ██   '
echo '  ███████  ██     ███  ██     ██     ██████  ██████   ███████     '
echo
echo '                             UNIT3D Package for Ubuntu 16.04 '
echo -e "\n\n"

echo
echo "=========== Install overview ==========="
echo
echo "- PHP 7.1 (stable >= 7.1.2) with PHP-FPM"
echo "- Redis Server"
echo "- Nginx (stable >= 1.10)"
echo "- MySQL (5.7+)"
echo "- Composer"
echo "- UNIT3D"
echo

read -p 'Are you ready to install right now? [y/n]): ' answer
if [ "$answer" != 'y' ] && [ "$answer" != 'Y'  ]; then
    clear
    echo 'Maybe Next Time Then...'
    exit 1
fi

echo "- System utils installing..."
apt-get install mc zip unzip htop python-software-properties software-properties-common build-essential -y > /dev/null 2>&1

add-apt-repository ppa:ondrej/php -y > /dev/null 2>&1
apt-get update -y --force-yes -qq > /dev/null 2>&1


echo
echo "=========== Install PHP7.1 with modules: ==========="
echo

echo "1) php7.1-fpm"
echo "2) php7.1-common"
echo "3) php7.1-gd"
echo "4) php7.1-mysql"
echo "5) php7.1-curl"
echo "6) php7.1-cli"
echo "7) php-pear"
echo "8) php7.1-dev"
echo "9) php7.1-imap"
echo "10) php7.1-mcrypt"
echo "11) php7.1-readline"
echo "12) php7.1-mbstring"
echo "13) php7.1-json"
echo "14) php7.1-zip"
echo "15) php7.1-memcached"
echo "16) php7.1-imagick"
echo "17) php7.1-xml"

echo
echo "- Installing, please wait..."
echo

apt-get install php7.1-fpm php7.1-common php7.1-gd php7.1-mysql php7.1-curl php7.1-cli php-pear php7.1-dev php7.1-imap php7.1-mcrypt php7.1-readline php7.1-mbstring php7.1-json php7.1-zip memcached php7.1-memcached php7.1-imagick php7.1-xml imagemagick -y --force-yes -qq > /dev/null 2>&1

echo
echo "==> PHP7.1 installed succesful!"
echo

echo
echo "=========== Install Redis ==========="
echo

apt-add-repository ppa:chris-lea/redis-server -y
apt-get update -y --force-yes -qq > /dev/null 2>&1

echo
echo "==> Redis installed succesful!"
echo

echo
echo "=========== Install Nginx ==========="
echo

add-apt-repository ppa:nginx/stable -y > /dev/null 2>&1
apt-get update -y --force-yes -qq > /dev/null 2>&1

echo
echo "- Installing, please wait..."
echo

apt-get install nginx -y --force-yes -qq > /dev/null 2>&1

rm /etc/nginx/sites-available/default > /dev/null 2>&1
wget https://raw.githubusercontent.com/globalmac/Larascale/master/nginx/default7_1 -O /etc/nginx/sites-available/default > /dev/null 2>&1

echo
echo "==> Nginx installed succesful!"
echo

echo
echo "=========== MySQL ==========="
echo

# MySQL INSTALL CODE HERE!

echo
echo "==> MySQL installed succesful!"
echo

echo
echo "=========== Adding UNIT3D user ==========="
echo

useradd -g sudo -d /var/www/UNIT3D -m -s /bin/bash UNIT3D > /dev/null 2>&1
UNIT3D_password=$(gen_pass)

echo -e "$UNIT3D_password\n$UNIT3D_password\n" | passwd UNIT3D > /dev/null 2>&1

mkdir -p /var/www/UNIT3D > /dev/null 2>&1
chown -R UNIT3D:www-data /var/www/UNIT3D > /dev/null 2>&1

echo
echo "==> User UNIT3D - added successfully!"
echo

echo
echo "=========== Installing Composer ==========="
echo

cd /var/www/UNIT3D
curl -sS https://getcomposer.org/installer | php > /dev/null 2>&1
mv composer.phar /usr/local/bin/composer > /dev/null 2>&1 > /dev/null 2>&1

echo
echo "==> Composer installed succesful!"
echo


echo
echo "=========== Clone UNIT3D Repo ==========="
echo

cd /var/www/UNIT3D
# CLONE CODE HERE!

echo
echo "==> UNIT3D cloned succesful!"
echo


echo
echo "=========== Set UNIT3D DIR Permissions ==========="
echo

chown -R UNIT3D:www-data /var/www/UNIT3D > /dev/null 2>&1
cd /var/www/UNIT3D
chmod -R 777 storage > /dev/null 2>&1
chmod -R 777 bootstrap/cache > /dev/null 2>&1

echo
echo "==> UNIT3D Permissions Set Succesful!"
echo

echo
echo "=========== Restarting PHP, MySQL and NGINX ==========="
echo

service php7.1-fpm restart > /dev/null 2>&1
service nginx restart > /dev/null 2>&1
service mysql restart > /dev/null 2>&1

echo
echo "==> Restart Succesful!"
echo

echo "==========="
echo "Script complete successfully! Your new UNIT3D is ready!"
echo "1) SSH user:"
echo "Login: UNIT3D"
echo "Password: $UNIT3D_password"
echo
echo "UNIT3D site is running on - http://$(ifconfig | grep -Eo 'inet (addr:)?([0-9]*\.){3}[0-9]*' | grep -Eo '([0-9]*\.){3}[0-9]*' | grep -v '127.0.0.1')"
echo ""
echo "==========="

apt-get clean -y > /dev/null 2>&1
apt-get autoclean -y > /dev/null 2>&1
apt-get autoremove -y > /dev/null 2>&1

exit 0

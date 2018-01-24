<p align="center">
    <img src="https://i.imgur.com/q4awiMm.png" alt="UNIT3D Logo">
</p>
<p align="center">
    <b>A Special Thanks To All Our <a href="https://github.com/UNIT3D/UNIT3D/graphs/contributors">Contributors</a></b>
</p>
<hr>

<p align="center">
<a href="https://github.com/HDVinnie/UNIT3D"><img src="https://cdn.rawgit.com/sindresorhus/awesome/d7305f38d29fed78fa85652e3a63e154dd8e8829/media/badge.svg" /></a> <a class="badge-align" href="https://www.codacy.com/app/HDVinnie/UNIT3D?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=UNIT3D/UNIT3D&amp;utm_campaign=Badge_Grade"><img src="https://api.codacy.com/project/badge/Grade/6c6c6c940aec420e987ea82adea272ee"/></a> <a href="https://codeclimate.com/github/HDVinnie/UNIT3D/maintainability"><img src="https://api.codeclimate.com/v1/badges/69b1bed95964c8d1d951/maintainability" /></a> <a href="https://codeclimate.com/github/HDVinnie/UNIT3D/test_coverage"><img src="https://api.codeclimate.com/v1/badges/69b1bed95964c8d1d951/test_coverage" /></a> <a href="https://discord.gg/Yk4NBUU"><img alt="Discord chat" src="https://cdn.rawgit.com/Hyleus/237f9903320939eb4f7615633a8fb221/raw/dca104edf34eabaca1016e633f06a52a346a7700/chat-Discord-blue.svg" /></a> <a href="http://laravel.com"><img src="https://img.shields.io/badge/Laravel-5.5.31-f4645f.svg" /></a> <a href="https://github.com/UNIT3D/UNIT3D/blob/master/LICENSE"><img src="https://img.shields.io/aur/license/yaourt.svg" /></a>
</p>


## Table of Contents

1. [Introduction](#introduction)
2. [Some Features](#features)
3. [Requirements](#requirements)
4. [Installation](#installation)
5. [Packages](#packages)
6. [Security](#security)
7. [Contributing](#contributing)
8. [License](#license)
9. [Screenshots](#screenshots)
10. [Homestead](#homestead)


## <a name="introduction"></a> Introduction

For the last year, I have been developing a Nex-Gen Torrent Tracker Script called "UNIT3D." This is a PHP script based off the lovely Laravel Framework -- currently Laravel Framework 5.5.31, MySQL Strict Mode Compliant and PHP 7.1 Ready. The code is well-designed and follows the PSR-2 coding style. It uses a MVC Architecture to ensure clarity between logic and presentation. As a hashing algorithm Bcrypt is used, to ensure a safe and proper way to store the passwords for the users. A lightweight Blade Templating Engine. Caching System Supporting: "apc,” "array,” "database,” "file," "memcached," and "redis" methods. Eloquent and much more!

## <a name="features"></a> Some Features

UNIT3D currently offers the following features:
  - Internal Forums System
  - Staff Dashboard
  - Faceted Ajax Torrent Search System
  - BON Store
  - Torrent Request Section with BON Bounties
  - Freeleech System
  - Double Upload System
  - Featured Torrents System
  - Polls System
  - Extra-Stats
  - PM System
  - Multilingual Support
  - and MUCH MORE!

## <a name="requirements"></a> Requirements

- A Web server (NGINX is recommended)
- PHP 7.0+ is required
- Dependencies for PHP, (Updated as issues spotted)
  -   php-gettext -> This is primarly for phpmyadmin, if you're going to use it, there has been cases where it does not install when installing phpmyadmin.
  -   php-curl -> This is specifically needed for the various APIs we have running.
- Crontab access
- A Redis server
- MySQL 5.7
- A decent dedicated server. Dont try running this on some crappy server!
<pre>
Processor: Intel  Xeon E3-1245v2 -
Cores/Threads: 4c/8t
Frequency: 3.4GHz /3.8GHz
RAM: 32GB DDR3 1333 MHz
Disks: SoftRaid  2x240 GB   SSD
Bandwidth: 250 Mbps
Traffic: Unlimited
<b>Is Under $50 A Month</b>
</pre>


## <a name="installation"></a> Installation

1. First grab the source-code and upload it to your web server. (If you have Git on your web server installed then clone it directly on your web server.)
2. Open a terminal and SSH into your server.
3. cd to the sites root directory
4. run `chmod +x composer-setup.sh && ./composer-setup.sh && php composer install`
5. Edit your `.env` file with your APP, DB, REDIS and MAIL info.
6. Run `php artisan key:generate` to generate your cipher key.
7. Edit `config/api-keys.php`, `config/app.php` and `config/other.php` (These house some basic settings. Be sure to visit the config manager from staff dashboard after up and running.)
8. Run  `php artisan migrate --seed` (Migrates All Tables And Foreign Keys)
9. Add   `* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1` to crontab
10. Go to your sites URL.
11. Login with the username `UNIT3D` and the password `UNIT3D`. (This is the default owner account.)
12. Enjoy using UNIT3D.

## <a name="packages"></a> Packages
Here are some packages that are built for UNIT3D.
- [An artisan package to import a XBTIT database into UNIT3D](https://github.com/pxgamer/xbtit-to-unit3d).
- [An artisan package to import a Gazelle database into UNIT3D](https://github.com/pxgamer/gazelle-to-unit3d).
- [An artisan package to import a U-232 database into UNIT3D](https://github.com/pxgamer/u232-to-unit3d).

## <a name="security"></a> Security

If you discover any security related issues, please email unit3d@protonmail.com instead of using the issue tracker.

## <a name="contributing"></a> Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## <a name="license"></a> License

UNIT3D is open-sourced software licensed under the [GNU General Public License v3.0](https://github.com/HDVinnie/UNIT3D/blob/master/LICENSE).

<b> As per license do not remove the license from sourcecode or from footer in /resources/views/partials/footer.blade.php</b>


<hr>

## <a name="screenshots"></a> Screenshots

<p align="center">
Torrent Details (Light Theme)
    <img src="https://i.imgur.com/l8DbExT.gif" alt="Torrent Details Page">
User Profile (Light Theme)
    <img src="https://i.imgur.com/94XCo3Q.gif" alt="User Profile Page">
BON Store (Dark Theme)
    <img src="https://i.imgur.com/7PPEiNT.gif" alt="BON Store Page">
</p>

## <a name="homestead"></a> Homestead

<a href="https://laravel.com/docs/5.4/homestead#installation-and-setup">Install and Setup Homestead </a>
### Example `Homestead.yaml`
```yaml
folders:
    - map: ~/projects
      to: /home/vagrant/projects

sites:
    ...
    - map: unit3d.site
      to: /home/vagrant/projects/www/unit3d/public

databases:
    - homestead
    - unit3d
```

### Example `/etc/hosts`
```
127.0.0.1       localhost
127.0.1.1       3rdtech-gnome

...
192.168.10.10   unit3d.site

```

1. run `cd ~/Homestead && vagrant up --provision`
2. run `vagrant ssh`
3. cd to the unit3d project root directory
4. copy `.env.example` to `.env`
5. run `php artisan key:generate`
6. run `composer install`
7. run `npm install`
8. run `php artisan migrate:refresh --seed`
9. visit <a href="http://unit3d.site">unit3d.site</a>
10. Login u: `UNIT3D` p: `UNIT3D`

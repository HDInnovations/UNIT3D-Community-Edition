<p align="center">
    <img src="https://i.imgur.com/CWez49j.png" alt="UNIT3D Logo">
</p>
<hr>

<p align="center">
<a href="https://github.com/HDVinnie/UNIT3D"><img src="https://cdn.rawgit.com/sindresorhus/awesome/d7305f38d29fed78fa85652e3a63e154dd8e8829/media/badge.svg" /></a>
<a href="https://codeclimate.com/github/HDVinnie/UNIT3D/maintainability"><img src="https://api.codeclimate.com/v1/badges/69b1bed95964c8d1d951/maintainability" /></a>
<a href="https://codeclimate.com/github/HDVinnie/UNIT3D/test_coverage"><img src="https://api.codeclimate.com/v1/badges/69b1bed95964c8d1d951/test_coverage" /></a>
<a href="https://github.com/HDVinnie/UNIT3D"><img alt="star this repo" src="http://githubbadges.com/star.svg?user=HDVinnie&repo=UNIT3D&style=flat&color=fff&background=7289DA" /></a>
</p>

<p align="center">
<b>Repo Not Yet Complete -- v1.0 of codebase coming soon</b>
</p>

## Table of Contents
1. [Introduction](#introduction)
2. [Some Features](#features)
3. [Requirements](#requirements)
4. [Installation](#installation)
5. [License](#license)
6. [Screenshots](#screenshots)


## <a name="introduction"></a> Introduction

For the last year, I have been developing a Nex-Gen Torrent Tracker Script called "UNIT3D." This is a PHP script based off the lovely Laravel Framework -- currently Laravel Framework 5.4.36, MySQL Strict Mode Compliant and PHP 7.1 Ready. The code is clean and to PSR-2 coding style standards. Using MVC Architecture to ensure clarity between logic and presentation. Bcrypt hashing algorithm for generating encrypted representation of a password. A lightweight Blade Templating Engine. Caching System Supporting: "apc,” "array,” "database,” "file," "memcached," and "redis" methods. Eloquent and much more!

## <a name="features"></a> Some Features

UNIT3D currently has the following features:
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
  - and MUCH MORE!

## <a name="requirements"></a> Requirements

- Web server (NGINX Recommended)
- PHP 7.0+ is required
- Dependencies for PHP, (Updated as issues spotted)
  -   php-gettext -> This is primarly for phpmyadmin, if your going to use it, there has been cases where it does not install when installing phpmyadmin.
  -   php-curl -> This is specifically needed for the Various API's we have running.
- Crontab access
- Redis Server

## <a name="installation"></a> Installation

1. First grab the source and upload dir to your web directory
2. Open up terminal and SSH into your server.
3. Go to the script dir and download [composer](https://getcomposer.org/download/) and run `composer update`
4. When all libraries are installed edit `config/api-keys.php`, `config/app.php` and `config/other.php` (These house some basic settings. Be sure to visit the config manager from staff dashboard after up and running.)
5. Run  `php artisan migrate` (Migrates All Tables And Foreign Keys)
6. Run `php artisan db:seed --class=UsersTableSeeder` (Seeds Default Owner Account and 2 System Bot Accounts To Users Table)
7. Run `composer require predis/predis`
8. Run `composer update`
9. Add   <code>* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1</code>   to crontab
10. Go to your web url
11. Login using Username: UNIT3D & Password: UNIT3D (Default Owner Account)
12. Enjoy

## <a name="license"></a> License

UNIT3D is open-sourced software licensed under the [GNU General Public License v3.0](https://github.com/HDVinnie/UNIT3D/blob/master/LICENSE).


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

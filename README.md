<p align="center">
    <img src="https://i.imgur.com/q4awiMm.png" alt="UNIT3D Logo">
</p>
<p align="center">
    💛<b>A Big Thanks To All Our <a href="#contributors">Contributors</a> and Patrons</b>💛
</p>
<hr>

<p align="center">
<a href="http://laravel.com"><img src="https://img.shields.io/badge/Laravel-5.8.29-f4645f.svg?style=flat-square" /></a> 
<a href="https://github.com/HDInnovations/UNIT3D/blob/master/LICENSE"><img src="https://img.shields.io/badge/License-AGPL%20v3.0-yellow.svg?style=flat-square" /></a>
<a href="https://github.styleci.io/repos/113471037"><img src="https://github.styleci.io/repos/113471037/shield?branch=master" alt="StyleCI"></a>
<a class="badge-align" href="https://www.codacy.com/app/HDVinnie/UNIT3D?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=UNIT3D/UNIT3D&amp;utm_campaign=Badge_Grade"><img src="https://api.codacy.com/project/badge/Grade/6c6c6c940aec420e987ea82adea272ee"/></a>
<a href="https://discord.gg/ntnPxzG"><img alt="Discord chat" src="https://img.shields.io/badge/discord-Chat%20Now-a29bfe.svg?style=flat-square" /></a>
<a href="https://www.patreon.com/HDInnovations"><img src="https://img.shields.io/badge/patreon-Support%20UNIT3D-orange.svg?style=flat-square"></a>
<a href="https://observatory.mozilla.org/analyze/unit3d.org"><img src="https://img.shields.io/badge/A+-Mozilla%20Observatory-blueviolet.svg?style=flat-square"></a>
<a href="http://makeapullrequest.com"><img src="https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square"></a>
</p>


## Table of Contents

1. [Introduction](#introduction)
2. [Some Features](#features)
3. [Requirements](#requirements)
4. [Installation](#installation)
4.1 [Automated-Installer](#auto-install)
4.2 [Manual Install](#manual-install)
5. [Updating](#updating)
6. [Packages](#packages)
7. [Version Support Information](#versions)
8. [Security](#security)
9. [Contributing](#contributing)
10. [License](#license)
11. [Homestead (For local developement)](#homestead)
12. [Demo](#demo)
13. [Donate](#donate)
14. [Contributors](#contributors)
15. [Special Thanks](#thanks)


## <a name="introduction"></a> :page_facing_up: Introduction

I have been developing a Nex-Gen Torrent Tracker Software called "UNIT3D." This is a PHP software based off the lovely Laravel Framework -- currently Laravel Framework 5.8.29, MySQL Strict Mode Compliant and PHP 7.1 Ready. The code is well-designed and follows the PSR-2 coding style. It uses a MVC Architecture to ensure clarity between logic and presentation. As a hashing algorithm of Bcrypt or Argon2 is used, to ensure a safe and proper way to store the passwords for the users. A lightweight Blade Templating Engine. Caching System Supporting: "apc,” "array,” "database,” "file," "memcached," and "redis" methods. Eloquent and much more!

## <a name="features"></a> 💎 Some Features

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
  - TwoStep Auth System
  - DB + Files Backup Manager
  - RSS System
  - and MUCH MORE!

## <a name="requirements"></a> :white_check_mark: Requirements

- A Web server (NGINX is recommended)
- PHP 7.1.3 + is required (7.2 for Argon2 Support)
- Dependencies for PHP,
  -   php-curl -> This is specifically needed for the various APIs we have running.
  -   php-intl -> This is required for the Spatie\SslCertificate.
  -   php-zip -> This is required for the Backup Manager.
- Crontab access
- A Redis server
- MySQL 5.7 + or MariaDB 10.2 +
- TheMovieDB API Key: https://www.themoviedb.org/documentation/api
- OMDB API Key: http://www.omdbapi.com/
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

## <a name="installation"></a> :computer: Installation
```
NOTE: If you are running UNIT3D on a non HTTPS instance you MUST change the following configs.

config/session.php  <-- HTTPS Only Cookies must be set to false
config/secure-headers.php   <-- HTTP Strict Transport Security must be set to false
config/secure-headers.php   <-- Content Security Policy must be disabled
```

### <a name="auto-install"></a> Automated Installer
**A UNIT3D Installer has been released by Poppabear.**

<pre>
git clone https://github.com/poppabear8883/UNIT3D-INSTALLER.git installer
cd installer
sudo ./install.sh
</pre>

Check it out here for more information: https://github.com/poppabear8883/UNIT3D-INSTALLER

Video Tutorial Can Be Seen Here:
https://www.youtube.com/watch?v=f2tiMWZ3KbA

### <a name="manual-install"></a> Manual Install
If you rather setup UNIT3D manually you can follow the instructions here: http://docs.unit3d.org/manual_install.html

## <a name="updating"></a> :book: Documentation
Repo - https://github.com/HDInnovations/UNIT3D-Docs
Site - http://docs.unit3d.org

## <a name="docs"></a> :computer: Updating
`php artisan git:update`

Video Tutorial Can Be Seen Here:
https://www.youtube.com/watch?v=tlNUjS1dYMs
 
## <a name="packages"></a> 📦 Packages
Here are some packages that are built for UNIT3D.
- [An artisan package to import a XBTIT database into UNIT3D](https://github.com/HDInnovations/xbtit-to-unit3d).
- [An artisan package to import a Gazelle database into UNIT3D](https://github.com/HDInnovations/gazelle-to-unit3d).
- [An artisan package to import a U-232 database into UNIT3D](https://github.com/HDInnovations/u232-to-unit3d).

## <a name="versions"></a> 🚨 Version Support Information
 Version     | Status                   | PHP Version Required
:------------|:-------------------------|:------------
 1.9.4       |  Active Support :rocket: | >= 7.3
 1.9.3       |  Active Support :rocket: | >= 7.3
 1.0 to 1.9.2|  End Of Life :skull:     | >= 7.1.3

## <a name="security"></a> :lock: Security

If you discover any security related issues, please email unit3d@protonmail.com instead of using the issue tracker.

## <a name="contributing"></a> :muscle: Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## <a name="license"></a> 📝 License

UNIT3D is open-sourced software licensed under the [GNU General Public License v3.0](https://github.com/HDInnovations/UNIT3D/blob/master/LICENSE).

<b> As per license do not remove the license from sourcecode or from footer in `/resources/views/partials/footer.blade.php`</b>

## <a name="demo"></a> :computer: Demo

URL: https://unit3d.org

Username: UNIT3D

Password: UNIT3D

Demo is reset every 48 hours!

## <a name="donate"></a> :star: Support UNIT3D

You can support my work if you are enjoying UNIT3D, this really keeps me up for fixing problems and adding new features. Also helps pay for the demo server + domain. Plus some beer to keep me sane. Some folks have asked me if it's possible to do a one-time donation, or if I accept cryptocurrency. Yes, and yes!

	
Patreon:<a href="https://www.patreon.com/HDInnovations"><img src="https://img.shields.io/badge/patreon-Support%20UNIT3D-orange.svg"></a>

Bitcoin (BTC) - 3HUVkv3Q8b5nbxa9DtXG1dm4RdTJaTFRfc

Bitcoin Cash (BCH) - qp3wgpnwzpj4v9sq90wflsca8p5s75glrvga9tweu2

Ether (ETH) - 0x5eFF42F65234aD9c6A0CA5B9495f3c6D205bBC27

Litecoin (LTC) - MDLKyHzupt1mchuo8mrjW9mihkKp1LD4nG

## <a name="contributors"></a> :blue_heart: Contributors

Thanks goes to these wonderful people ([emoji key](https://github.com/all-contributors/all-contributors#emoji-key)):

<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore -->
<table><tr><td align="center"><a href="https://github.com/HDVinnie"><img src="https://avatars2.githubusercontent.com/u/12850699?v=4" width="100px;" alt="HDVinnie"/><br /><sub><b>HDVinnie</b></sub></a><br /><a href="https://github.com/HDInnovations/UNIT3D/commits?author=HDVinnie" title="Code">💻</a> <a href="#design-HDVinnie" title="Design">🎨</a> <a href="https://github.com/HDInnovations/UNIT3D/commits?author=HDVinnie" title="Documentation">📖</a></td><td align="center"><a href="https://github.com/poppabear8883"><img src="https://avatars1.githubusercontent.com/u/7263458?v=4" width="100px;" alt="Everett (Mike) Wiley"/><br /><sub><b>Everett (Mike) Wiley</b></sub></a><br /><a href="https://github.com/HDInnovations/UNIT3D/commits?author=poppabear8883" title="Code">💻</a></td><td align="center"><a href="https://github.com/singularity43"><img src="https://avatars1.githubusercontent.com/u/46550600?v=4" width="100px;" alt="singularity43"/><br /><sub><b>singularity43</b></sub></a><br /><a href="https://github.com/HDInnovations/UNIT3D/commits?author=singularity43" title="Code">💻</a></td><td align="center"><a href="https://github.com/VerioPL"><img src="https://avatars1.githubusercontent.com/u/24521644?v=4" width="100px;" alt="VerioPL"/><br /><sub><b>VerioPL</b></sub></a><br /><a href="#translation-VerioPL" title="Translation">🌍</a></td><td align="center"><a href="https://github.com/artyuum"><img src="https://avatars0.githubusercontent.com/u/17199757?v=4" width="100px;" alt="Artyum"/><br /><sub><b>Artyuum</b></sub></a><br /><a href="#design-artyuum" title="Design">🎨</a></td><td align="center"><a href="https://github.com/pbodq2"><img src="https://avatars0.githubusercontent.com/u/25418300?v=4" width="100px;" alt="Morgan Wong"/><br /><sub><b>Morgan Wong</b></sub></a><br /><a href="#translation-pbodq2" title="Translation">🌍</a></td></tr></table>

<!-- ALL-CONTRIBUTORS-LIST:END -->

## <a name="thanks"></a> :heart: Special Thanks

<a href="https://www.jetbrains.com/store/?fromMenu#edition=personal"><img src="https://i.imgur.com/KgDXZV8.png"></a>
<a href="https://www.themoviedb.org/"><img src="https://www.themoviedb.org/assets/2/v4/logos/208x226-stacked-green-9484383bd9853615c113f020def5cbe27f6d08a84ff834f41371f223ebad4a3c.png"></a>

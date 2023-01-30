<p align="center">
    <img src="https://i.postimg.cc/GpMQ3bj2/68747470733a2f2f692e706f7374696d672e63632f765a623674706e772f53637265656e2d53686f742d323032312d31302d.png" alt="UNIT3D-Community-Edition Cover Image">
</p>

<p align="center">
<a href="http://laravel.com"><img src="https://img.shields.io/badge/Laravel-9-f4645f.svg" /></a> 
<a href="https://github.com/HDInnovations/UNIT3D/blob/master/LICENSE"><img src="https://img.shields.io/badge/License-AGPL%20v3.0-yellow.svg" /></a>
<a href="https://github.com/HDInnovations/UNIT3D-Community-Edition/actions/workflows/lint.yml/badge.svg"><img src="https://github.com/HDInnovations/UNIT3D-Community-Edition/actions/workflows/lint.yml/badge.svg" /></a>
<a href="https://github.com/HDInnovations/UNIT3D-Community-Edition/actions/workflows/phpunit-test.yml/badge.svg"><img src="https://github.com/HDInnovations/UNIT3D-Community-Edition/actions/workflows/phpunit-test.yml/badge.svg" /></a>
<a href="https://github.com/HDInnovations/UNIT3D-Community-Edition/actions/workflows/compile-assets-test.yml/badge.svg"><img src="https://github.com/HDInnovations/UNIT3D-Community-Edition/actions/workflows/compile-assets-test.yml/badge.svg" /></a>
<a href="https://discord.gg/J8dsx7F5yT"><img alt="Discord chat" src="https://img.shields.io/badge/discord-Chat%20Now-a29bfe.svg" /></a>
<a href="http://makeapullrequest.com"><img src="https://img.shields.io/badge/PRs-welcome-brightgreen.svg"></a>
</p>

<a href=https://github.com/sponsors/HDVinnie>
<p align="center">
    <img src="https://i.postimg.cc/QMRRNgmV/support.png" alt="UNIT3D-Community-Edition Support Image">
</p>
</a>

<p align="center">
    🎉<b>A Big Thanks To All Our <a href="https://github.com/HDInnovations/UNIT3D-Community-Edition/graphs/contributors">Contributors</a> and <a href="https://github.com/sponsors/HDVinnie">Sponsors</a></b>🎉
</p>

## 📝 Table of Contents

1. [Introduction](#introduction)
2. [Some Features](#features)
3. [Requirements](#requirements)
4. [Installation](#installation)
4.1 [Automated-Installer](#auto-install)
5. [Updating](#updating)
6. [Version Support Information](#versions)
7. [Security](#security)
8. [Contributing](#contributing)
9. [License](#license)
10. [Demo](#demo)
11. [Discord-Chat](#chat)
12. [Sponsoring](#sponsor)
13. [Special Thanks](#thanks)


## <a name="introduction"></a> 🧐 Introduction

We have been developing a Private Torrent Tracker Software called `UNIT3D`. This is a PHP software based on Laravel 9, Livewire and AlpineJS. It is MySQL Strict Mode Compliant, and PHP 8.2 Ready. The code is well-designed and follows the PSR-2 coding style. It uses an MVC Architecture to ensure clarity between logic and presentation. As a hashing algorithm of Bcrypt or Argon2 is used, to ensure a safe and proper way to store the passwords for the users. A lightweight Blade Templating Engine. Caching System Supporting: "apc,” "array,” "database,” "file," "memcached," and "redis" methods. Eloquent and much more!

## <a name="features"></a> 💎 Some Features

UNIT3D currently offers the following features:
  - Internal Forums System
  - Staff Dashboard
  - Livewire Powered Search Systems (Torrents, Requests, Users, Etc)
  - Bonus Points + Store
  - Torrent Request Section with Bonus Point Bounties and votes
  - Freeleech System
  - Double Upload System
  - Featured Torrents System
  - Polls System
  - Extra-Stats
  - Torrent Grouping
  - Top 10 System
  - PM System
  - Multilingual Support
  - TwoStep Auth System
  - DB + Files Backup Manager
  - RSS System
  - and MUCH MORE!

## <a name="requirements"></a> ☑️ Requirements

- A Web server (NGINX is recommended)
- PHP 8.1 + is required
- Crontab access
- A Redis server
- MySQL 8.0 + or MariaDB 10.2 +
- TheMovieDB API Key: https://www.themoviedb.org/documentation/api
- A good dedicated server. Dont try running this on some basic server if you plan to run a large tracker!


## <a name="installation"></a> 🖥️ Installation
```
NOTE: If you are running UNIT3D on a non HTTPS instance you MUST change the following configs.

.env  <-- SESSION_SECURE_COOKIE must be set to false
config/secure-headers.php   <-- HTTP Strict Transport Security must be set to false
config/secure-headers.php   <-- Content Security Policy must be disabled
```

### <a name="auto-install"></a> Automated Installer
**A UNIT3D Installer has been released by Poppabear.**

**Officially Supported OS's**
- Ubuntu 20.04 LTS

```bash
git clone https://github.com/poppabear8883/UNIT3D-INSTALLER.git installer
cd installer
sudo ./install.sh
```

Check it out here for more information: https://github.com/poppabear8883/UNIT3D-INSTALLER

### Demo Data

Use this command to generate demo users and torrents for testing purposes:

`php artisan demo:seed`

## <a name="updating"></a> 🖥️ Updating
`php artisan git:update`

## <a name="versions"></a> 🚨 Version Support Information
 Version     | Status                   | PHP Version Required
:------------|:-------------------------|:------------
 6.x.x       |  Active Support :rocket: | >= 8.1
 5.x.x       |  End Of Life :skull: | >= 8.0
 4.x.x       |  End Of Life :skull: | >= 7.4
 3.x.x       |  End Of Life :skull: | >= 7.4
 2.3.0 to 2.7.0|  End Of Life :skull: | >= 7.4
 2.0.0 to 2.2.7|  End Of Life :skull: | >= 7.3
 1.0 to 1.9.4|  End Of Life :skull:     | >= 7.1.3

## <a name="security"></a> 🔐 Security

If you discover any security related issues, please email hdinnovations@protonmail.com instead of using the issue tracker.

## <a name="contributing"></a> ✍️ Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## <a name="license"></a> 📝 License

UNIT3D is open-sourced software licensed under the [GNU Affero General Public License v3.0](https://github.com/HDInnovations/UNIT3D/blob/master/LICENSE).

## <a name="demo"></a>  🖥️ Demo

URL: https://unit3d.site

Username: UNIT3D

Password: UNIT3D

Demo is reset every 72 hours!

## <a name="chat"></a>  💬 Come Chat With Us

URL: https://discord.gg/J8dsx7F5yT

## <a name="sponsor"></a> ✨ Sponsor UNIT3D (HDInnovations / HDVinnie)

You can support my work if you are enjoying UNIT3D or need support via Discord. 

Monthy Recurring:

https://github.com/sponsors/HDVinnie?frequency=recurring&sponsor=HDVinnie

One-time Custom Amount:

https://github.com/sponsors/HDVinnie?frequency=one-time&sponsor=HDVinnie

Some folks have asked me if it's possible to do a one-time donation via Crypto Currency or CashApp. Yes! If you would like to contribute via a crypto-currency not listed please let me know.

CashApp - $hdvinnie

Bitcoin (BTC) - 3HUVkv3Q8b5nbxa9DtXG1dm4RdTJaTFRfc

Bitcoin Cash (BCH) - qp3wgpnwzpj4v9sq90wflsca8p5s75glrvga9tweu2

Ether (ETH) - 0x5eFF42F65234aD9c6A0CA5B9495f3c6D205bBC27
    
ETC - 0xd644C7C7009eC3824f3305ff6C7E2Ee90497d56e    

Litecoin (LTC) - MDLKyHzupt1mchuo8mrjW9mihkKp1LD4nG

USDC - 0xB32102d9104d2bfd0D4E3E4069618ADD985a4e2E

USDT (ERC-20) - 0x24c79c41EEAd9d81203ee567fE4bA3a6c81374DB

DOGE - DJ78fQspiu879y3adLbTZVSFABKhKqHE7B


## <a name="thanks"></a> 🎉 Special Thanks

<a href="https://www.jetbrains.com/?from=UNIT3D"><img src="https://i.imgur.com/KgDXZV8.png" height="50px;"></a>
<a href="https://www.themoviedb.org/"><img src="https://www.themoviedb.org/assets/2/v4/logos/v2/blue_square_2-d537fb228cf3ded904ef09b136fe3fec72548ebc1fea3fbbd1ad9e36364db38b.svg" height="50px;"></a>
<a href="https://github.com"><img src="https://i.imgur.com/NVWhzrU.png" height="50px;"></a>
<a href="https://laravel.com"><img src="https://i.postimg.cc/cCDBswfK/1200px-Laravel-svg.png" height="50px;"></a>
<a href="https://laravel-livewire.com"><img src="https://i.postimg.cc/jjsNyBbh/Livewire.png" height="50px;"></a>
<a href="https://alpinejs.dev"><img src="https://i.postimg.cc/28pWk0M1/alpinejs-logo.png" height="50px;"></a>
<a href="https://styleci.io"><img src="https://i.postimg.cc/0y4XN4yW/og.png" height="50px;"></a>

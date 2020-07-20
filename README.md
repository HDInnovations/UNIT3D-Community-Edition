<p align="center">
    <img src="https://i.imgur.com/PKv7tSA.png" alt="UNIT3D-Community-Edition Cover Image">
</p>
<p align="center">
    🎉<b>A Big Thanks To All Our <a href="https://github.com/HDInnovations/UNIT3D-Community-Edition/graphs/contributors">Contributors</a> and <a href="#sponsors">Sponsors</a></b>🎉
</p>
<hr>

<p align="center">
<a href="http://laravel.com"><img src="https://img.shields.io/badge/Laravel-6-f4645f.svg?style=flat-square" /></a> 
<a href="https://github.com/HDInnovations/UNIT3D/blob/master/LICENSE"><img src="https://img.shields.io/badge/License-AGPL%20v3.0-yellow.svg?style=flat-square" /></a>
<a href="https://travis-ci.org/HDInnovations/UNIT3D-Community-Edition?utm_medium=notification&utm_source=github_status"><img src="https://travis-ci.org/HDInnovations/UNIT3D-Community-Edition.svg?branch=master" /></a> 
<a href="https://github.styleci.io/repos/113471037"><img src="https://github.styleci.io/repos/113471037/shield?branch=master" alt="StyleCI"></a>
<a class="badge-align" href="https://www.codacy.com/manual/HDVinnie/UNIT3D?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=HDInnovations/UNIT3D&amp;utm_campaign=Badge_Grade"><img src="https://api.codacy.com/project/badge/Grade/6c6c6c940aec420e987ea82adea272ee"/></a>
<a href="https://codecov.io/gh/HDInnovations/UNIT3D-Community-Edition">
  <img src="https://codecov.io/gh/HDInnovations/UNIT3D-Community-Edition/branch/master/graph/badge.svg" />
</a>
<a href="https://discord.gg/Bf47vws"><img alt="Discord chat" src="https://img.shields.io/badge/discord-Chat%20Now-a29bfe.svg?style=flat-square" /></a>
<a href="https://observatory.mozilla.org/analyze/unit3d.site"><img src="https://img.shields.io/badge/A+-Mozilla%20Observatory-blueviolet.svg?style=flat-square"></a>
<a href="http://makeapullrequest.com"><img src="https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square"></a>
</p>


## 📝 Table of Contents

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
11. [Demo](#demo)
12. [Support/Chat](#chat)
13. [Sponsoring](#sponsor)
14. [Collaborators](#collaborators)
15. [Special Thanks](#thanks)
16. [Sponsors](#sponsors)


## <a name="introduction"></a> 🧐 Introduction

I have been developing a Nex-Gen Torrent Tracker Software called "UNIT3D." This is a PHP software based off the lovely Laravel Framework -- currently Laravel Framework 6, MySQL Strict Mode Compliant and PHP 7.4 Ready. The code is well-designed and follows the PSR-2 coding style. It uses a MVC Architecture to ensure clarity between logic and presentation. As a hashing algorithm of Bcrypt or Argon2 is used, to ensure a safe and proper way to store the passwords for the users. A lightweight Blade Templating Engine. Caching System Supporting: "apc,” "array,” "database,” "file," "memcached," and "redis" methods. Eloquent and much more!

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

## <a name="requirements"></a> ☑️ Requirements

- A Web server (NGINX is recommended)
- PHP 7.4 + is required
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

## <a name="installation"></a> 🖥️ Installation
```
NOTE: If you are running UNIT3D on a non HTTPS instance you MUST change the following configs.

.env  <-- SESSION_SECURE_COOKIE must be set to false
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
https://www.youtube.com/watch?v=tbIZpsz093o

### <a name="manual-install"></a> Manual Install
If you rather setup UNIT3D manually you can follow the instructions here: https://hdinnovations.github.io/UNIT3D-Docs/manual_install.html

## <a name="updating"></a> 📖 Documentation
Repo - https://github.com/HDInnovations/UNIT3D-Docs
Site - https://hdinnovations.github.io/UNIT3D-Docs/index.html

## <a name="docs"></a> 🖥️ Updating
`php artisan git:update`

Video Tutorial Can Be Seen Here:
https://www.youtube.com/watch?v=tlNUjS1dYMs
 
## <a name="packages"></a> 📦 Packages
Here are some packages that are built for UNIT3D.
- [An artisan package to import a xbtitFM database into UNIT3D](https://github.com/HDInnovations/xbtitfm-to-unit3d).
- [An artisan package to import a TemplateShares database into UNIT3D](https://github.com/HDInnovations/templateshares-to-unit3d).
- [An artisan package to import a Luminance database into UNIT3D](https://github.com/HDInnovations/luminance-to-unit3d).
- [An artisan package to import a XBTIT database into UNIT3D](https://github.com/HDInnovations/xbtit-to-unit3d).
- [An artisan package to import a Gazelle database into UNIT3D](https://github.com/HDInnovations/gazelle-to-unit3d).
- [An artisan package to import a U-232 database into UNIT3D](https://github.com/HDInnovations/u232-to-unit3d).

## <a name="versions"></a> 🚨 Version Support Information
 Version     | Status                   | PHP Version Required
:------------|:-------------------------|:------------
 2.5.x       |  Active Support :rocket: | >= 7.4
 2.4.x       |  Active Support :rocket: | >= 7.4
 2.3.x       |  Active Support :rocket: | >= 7.4
 2.0.0 to 2.2.7|  End Of Life :skull: | >= 7.3
 1.0 to 1.9.4|  End Of Life :skull:     | >= 7.1.3

## <a name="security"></a> 🔐 Security

If you discover any security related issues, please email hdinnovations@protonmail.com instead of using the issue tracker.

## <a name="contributing"></a> ✍️ Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## <a name="license"></a> 📝 License

UNIT3D is open-sourced software licensed under the [GNU Affero General Public License v3.0](https://github.com/HDInnovations/UNIT3D/blob/master/LICENSE).

<b> As per license do not remove the license from sourcecode files
```
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */
```

 Or the credits from footer in `/resources/views/partials/footer.blade.php`
```
<li>
<a href="https://github.com/HDInnovations/UNIT3D-Community-Edition" target="_blank" class="btn btn-xs btn-primary">@lang('common.powered-by')</a>
</li>
```
</b>

## <a name="demo"></a>  🖥️ Demo

URL: https://unit3d.site

Username: UNIT3D

Password: UNIT3D

Demo is reset every 48 hours!

## <a name="chat"></a>  💬 Chat With Us

URL: https://discord.gg/Bf47vws

## <a name="sponsor"></a> ✨ Sponsor UNIT3D (HDInnovations / HDVinnie)

https://github.com/sponsors/HDVinnie

You can support my work if you are enjoying UNIT3D and my other projects under HDInnovations, this really keeps me up for fixing problems and adding new features. Also helps pay for the demo server + domain. Plus some beer to keep me sane. Some folks have asked me if it's possible to do a one-time donation via cryptocurrency. Yes!

Bitcoin (BTC) - 3HUVkv3Q8b5nbxa9DtXG1dm4RdTJaTFRfc

Bitcoin Cash (BCH) - qp3wgpnwzpj4v9sq90wflsca8p5s75glrvga9tweu2

Ether (ETH) - 0x5eFF42F65234aD9c6A0CA5B9495f3c6D205bBC27

Litecoin (LTC) - MDLKyHzupt1mchuo8mrjW9mihkKp1LD4nG

## <a name="sponsors"></a> 😍 Sponsors (Much Love!) 
<h5>(Private Sponsors are not listed. If you would like to be please email me.)</h5>
<h3>Top Level Tier = 💖 , Tier 4 = 💛, Tier 3 = 💚, Tier 2 = 💜, Tier 1 = 💙</h3>

<table>
    <tr>
        <td align="center">
            <a href="https://github.com/PyR8zdl"><img src="https://avatars2.githubusercontent.com/u/48926974?v=4" width="50px;" alt="PyR8zdl"/>
                <br />
                <sub><b>PyR8zdl</b></sub>
            </a>
            <br />
            <a href="https://github.com/sponsors/HDVinnie" title="Top Level Sponsor">💖</a>
        </td>
        <td align="center">
            <a href="https://github.com/Darkspac3"><img src="https://avatars2.githubusercontent.com/u/35001866?v=4" width="50px;" alt="Darkspac3"/>
                <br />
                <sub><b>Darkspac3</b></sub>
            </a>
            <br />
            <a href="https://github.com/sponsors/HDVinnie" title="Top Level Sponsor">💖</a>
        </td>
        <td align="center">
            <a href="https://github.com/NemoSciri"><img src="https://avatars2.githubusercontent.com/u/58690995?v=4" width="50px;" alt="NemoSciri"/>
                <br />
                <sub><b>NemoSciri</b></sub>
            </a>
            <br />
            <a href="https://github.com/sponsors/HDVinnie" title="Top Level Sponsor">💖</a>
        </td>
        <td align="center">
            <a href="https://github.com/OsamaBinLightBulb"><img src="https://avatars2.githubusercontent.com/u/46361079?v=4" width="50px;" alt="OsamaBinLightBulb"/>
                <br />
                <sub><b>OsamaBinLightBulb</b></sub>
            </a>
            <br />
            <a href="https://github.com/sponsors/HDVinnie" title="Tier 4 Sponsor">💛</a>
        </td>
        <td align="center">
            <a href="https://github.com/clandestine8"><img src="https://avatars0.githubusercontent.com/u/5833609?v=4" width="50px;" alt="clandestine8"/>
                <br />
                <sub><b>clandestine8</b></sub>
            </a>
            <br />
            <a href="https://github.com/sponsors/HDVinnie" title="Tier 4 Sponsor">💛</a>
        </td>
        <td align="center">
            <a href="https://github.com/STiCKYFiNGAZZ"><img src="https://avatars2.githubusercontent.com/u/26826902?v=4" width="50px;" alt="STiCKYFiNGAZZ"/>
                <br />
                <sub><b>STiCKYFiNGAZZ</b></sub>
            </a>
            <br />
            <a href="https://github.com/sponsors/HDVinnie" title="Tier 4 Sponsor">💛</a>
        </td>
    </tr>
</table>

<h5>Thank you to all out Tier 1, Tier 2 and Tier 3 Sponsors as well. While your not mentioned in the README I do appreciate your sponsorship!</h5>

## <a name="collaborators"></a> ✍️ Collaborators

Thanks goes to these wonderful people who have contributed alot of code:

<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore-start -->
<!-- markdownlint-disable -->
<table>
  <tr>
    <td align="center"><a href="https://github.com/HDVinnie"><img src="https://avatars2.githubusercontent.com/u/12850699?v=4" width="100px;" alt=""/><br /><sub><b>HDVinnie</b></sub></a><br /><a href="https://github.com/HDInnovations/UNIT3D-Community-Edition/commits?author=HDVinnie" title="Code">💻</a> <a href="#design-HDVinnie" title="Design">🎨</a> <a href="https://github.com/HDInnovations/UNIT3D-Community-Edition/commits?author=HDVinnie" title="Documentation">📖</a></td>
    <td align="center"><a href="https://github.com/poppabear8883"><img src="https://avatars1.githubusercontent.com/u/7263458?v=4" width="100px;" alt=""/><br /><sub><b>Everett (Mike) Wiley</b></sub></a><br /><a href="https://github.com/HDInnovations/UNIT3D-Community-Edition/commits?author=poppabear8883" title="Code">💻</a></td>
    <td align="center"><a href="https://github.com/singularity43"><img src="https://avatars1.githubusercontent.com/u/46550600?v=4" width="100px;" alt=""/><br /><sub><b>singularity43</b></sub></a><br /><a href="https://github.com/HDInnovations/UNIT3D-Community-Edition/commits?author=singularity43" title="Code">💻</a></td>
    <td align="center"><a href="https://github.com/VerioPL"><img src="https://avatars1.githubusercontent.com/u/24521644?v=4" width="100px;" alt=""/><br /><sub><b>VerioPL</b></sub></a><br /><a href="#translation-VerioPL" title="Translation">🌍</a></td>
    <td align="center"><a href="https://github.com/pbodq2"><img src="https://avatars0.githubusercontent.com/u/25418300?v=4" width="100px;" alt=""/><br /><sub><b>Morgan Wong</b></sub></a><br /><a href="#translation-pbodq2" title="Translation">🌍</a></td>
    <td align="center"><a href="https://nyamori.moe"><img src="https://avatars1.githubusercontent.com/u/5460071?v=4" width="100px;" alt=""/><br /><sub><b>Gyakkun</b></sub></a><br /><a href="#translation-gyakkun" title="Translation">🌍</a></td>
    <td align="center"><a href="https://indiehd.com"><img src="https://avatars2.githubusercontent.com/u/1236883?v=4" width="100px;" alt=""/><br /><sub><b>Ben Johnson</b></sub></a><br /><a href="https://github.com/HDInnovations/UNIT3D-Community-Edition/commits?author=cbj4074" title="Tests">⚠️</a> <a href="https://github.com/HDInnovations/UNIT3D-Community-Edition/commits?author=cbj4074" title="Code">💻</a></td>
  </tr>
</table>

<!-- markdownlint-enable -->
<!-- prettier-ignore-end -->
<!-- ALL-CONTRIBUTORS-LIST:END -->

## <a name="thanks"></a> 🎉 Special Thanks

<a href="https://www.jetbrains.com/store/?fromMenu#edition=personal"><img src="https://i.imgur.com/KgDXZV8.png" height="50px;"></a>
<a href="https://www.themoviedb.org/"><img src="https://www.themoviedb.org/assets/2/v4/logos/v2/blue_square_2-d537fb228cf3ded904ef09b136fe3fec72548ebc1fea3fbbd1ad9e36364db38b.svg" height="50px;"></a>
<a href="https://github.com"><img src="https://i.imgur.com/NVWhzrU.png" height="50px;"></a>
<a href="https://laravel.com"><img src="https://i.postimg.cc/cCDBswfK/1200px-Laravel-svg.png" height="50px;"></a>


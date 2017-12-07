<p align="center">
    <img src="https://i.imgur.com/CWez49j.png" alt="UNIT3D Logo">
</p>
<hr>


## Table of Contents
1. [Requirements](#requirements)
2. [Installation](#installation)
3. [License](#license)


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
4. When all libraries are installed edit `app/config/database.php`, `app/config/app.php` and `app/config/other.php` (These house some basic settings)
5. Run  `php artisan migrate`
6. Run `composer require predis/predis`
6. Run `composer update`
7. Add   <code>* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1</code>   to crontab
10. Go to your script url
11. Create an account from the site
12. In your database change your group in `the users` table, to ID 4 for administrators (Owner Rank)
13. Enjoy

## <a name="license"></a> License 

UNIT3D is open-sourced software licensed under the [GNU General Public License v3.0](https://github.com/HDVinnie/UNIT3D/blob/master/LICENSE).

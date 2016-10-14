PHP Seasons
=======
[![Build Status](https://img.shields.io/travis/JayBizzle/PHP-Seasons/master.svg?style=flat-square)](https://travis-ci.org/JayBizzle/PHP-Seasons) [![Total Downloads](https://img.shields.io/packagist/dt/JayBizzle/PHP-Seasons.svg?style=flat-square)](https://packagist.org/packages/jaybizzle/php-seasons)
[![MIT](https://img.shields.io/badge/license-MIT-ff69b4.svg?style=flat-square)](https://github.com/JayBizzle/PHP-Seasons) [![Version](https://img.shields.io/packagist/v/jaybizzle/PHP-Seasons.svg?style=flat-square)](https://packagist.org/packages/jaybizzle/php-seasons) [![StyleCI](https://styleci.io/repos/51580966/shield)](https://styleci.io/repos/51580966)

A small utility class that returns the meteorological season from a given date.

### Installation
Run `composer require jaybizzle/php-seasons dev-master` or add `"jaybizzle/php-seasons" :"dev-master"` to your `composer.json`.

### Usage
```PHP
use Jaybizzle\Seasons;

$season = new Seasons;

// Get season from date
$season->get('1st June');
    // Output: Summer

// Get current season
$season->get();
```

[![Analytics](https://ga-beacon.appspot.com/UA-72430465-1/PHP-Seasons/readme?pixel)](https://github.com/JayBizzle/PHP-Seasons)

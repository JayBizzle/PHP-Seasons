PHP Seasons
=======
[![Tests](https://github.com/JayBizzle/PHP-Seasons/actions/workflows/tests.yml/badge.svg)](https://github.com/JayBizzle/PHP-Seasons/actions/workflows/tests.yml) [![Total Downloads](https://img.shields.io/packagist/dt/JayBizzle/PHP-Seasons.svg?style=flat-square)](https://packagist.org/packages/jaybizzle/php-seasons)
[![MIT](https://img.shields.io/badge/license-MIT-ff69b4.svg?style=flat-square)](https://github.com/JayBizzle/PHP-Seasons) [![Version](https://img.shields.io/packagist/v/jaybizzle/PHP-Seasons.svg?style=flat-square)](https://packagist.org/packages/jaybizzle/php-seasons)

A lightweight PHP utility class that returns the season from a given date. Supports meteorological and astronomical season calculations, northern and southern hemispheres, and custom season names for translations.

### Installation

```bash
composer require jaybizzle/php-seasons
```

### Quick Start

```php
use Jaybizzle\Seasons;

// Get the current season
$season = new Seasons;
$season->season(); // e.g. "Spring"

// Get season for a specific date
$season->season('1st June'); // "Summer"

// Static convenience methods
Seasons::now();          // e.g. "Spring"
Seasons::for('1st June'); // "Summer"
```

### Season Calculation Methods

By default, seasons are calculated using **meteorological** boundaries based on whole calendar months:

| Season | Months |
|--------|--------|
| Winter | December, January, February |
| Spring | March, April, May |
| Summer | June, July, August |
| Autumn | September, October, November |

#### Astronomical Seasons

You can switch to **astronomical** season boundaries, which use approximate equinox and solstice dates:

| Season | Approximate Dates |
|--------|-------------------|
| Spring | March 20 - June 20 |
| Summer | June 21 - September 22 |
| Autumn | September 23 - December 21 |
| Winter | December 22 - March 19 |

```php
$season = new Seasons;

$season->season('March 19');                // "Winter" (meteorological: March = Spring)
$season->astronomical()->season('March 19'); // "Winter" (astronomical: before equinox)
$season->astronomical()->season('March 21'); // "Spring" (astronomical: after equinox)
```

The astronomical calculation adjusts automatically for leap years.

### Southern Hemisphere

Seasons in the southern hemisphere are reversed. Use the `southern()` method to get the correct season:

```php
$season = new Seasons;

$season->season('July');              // "Summer"
$season->southern()->season('July');  // "Winter"
```

This works with astronomical mode too:

```php
$season->astronomical()->southern()->season('March 25'); // "Autumn"
```

### Immutable Fluent API

The `astronomical()` and `southern()` methods return a **new instance**, leaving the original unchanged. This means you can safely reuse the same base instance:

```php
$season = new Seasons;

$northern = $season->season('July');              // "Summer"
$southern = $season->southern()->season('July');   // "Winter"
$original = $season->season('July');               // Still "Summer"
```

### Month Ranges

Get the month numbers that belong to a given season:

```php
$season = new Seasons;

$season->monthRange('Winter'); // [12, 1, 2]
$season->monthRange('Summer'); // [6, 7, 8]

// Southern hemisphere month ranges
$season->southern()->monthRange('Summer'); // [12, 1, 2]
```

### Translations

Pass an array of four season names to the constructor in the order: **winter, spring, summer, autumn**. All output will use your custom names:

```php
// French
$season = new Seasons(['Hiver', 'Printemps', 'Été', 'Automne']);
$season->season('June');        // "Été"
$season->season('January');     // "Hiver"
$season->monthRange('Été');     // [6, 7, 8]

// German
$season = new Seasons(['Winter', 'Frühling', 'Sommer', 'Herbst']);
$season->season('October');     // "Herbst"

// Japanese
$season = new Seasons(['冬', '春', '夏', '秋']);
$season->season('April');       // "春"
```

Translations work with all features including `southern()`, `astronomical()`, and `monthRange()`:

```php
$french = new Seasons(['Hiver', 'Printemps', 'Été', 'Automne']);
$french->southern()->season('June');                // "Hiver"
$french->astronomical()->season('March 21');        // "Printemps"
```

The static methods also accept translations:

```php
Seasons::for('June', ['Hiver', 'Printemps', 'Été', 'Automne']); // "Été"
Seasons::now(['Hiver', 'Printemps', 'Été', 'Automne']);          // e.g. "Printemps"
```

### Date Formats

Any date string parsable by PHP's [`strtotime()`](https://www.php.net/manual/en/function.strtotime.php) is accepted:

```php
$season = new Seasons;

$season->season('June');               // "Summer"
$season->season('1st October 2016');   // "Autumn"
$season->season('2024-12-25');         // "Winter"
$season->season('next Friday');        // depends on current date
```

An `Exception` is thrown if the date string cannot be parsed.

### API Reference

| Method | Returns | Description |
|--------|---------|-------------|
| `new Seasons(?array $names)` | `Seasons` | Create instance, optionally with custom season names |
| `season(?string $date)` | `string` | Get season for date (or current date if null) |
| `astronomical()` | `Seasons` | New instance using astronomical boundaries |
| `southern()` | `Seasons` | New instance using southern hemisphere |
| `monthRange(string $season)` | `array` | Get month numbers for a season |
| `Seasons::now(?array $names)` | `string` | Static: get current season |
| `Seasons::for(string $date, ?array $names)` | `string` | Static: get season for a date |

### Constants

```php
Seasons::SEASON_WINTER // "Winter"
Seasons::SEASON_SPRING // "Spring"
Seasons::SEASON_SUMMER // "Summer"
Seasons::SEASON_AUTUMN // "Autumn"
```

### License

MIT

[![Analytics](https://ga-beacon.appspot.com/UA-72430465-1/PHP-Seasons/readme?pixel)](https://github.com/JayBizzle/PHP-Seasons)

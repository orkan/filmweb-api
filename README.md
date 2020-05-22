![repository-open-graph-template7](https://user-images.githubusercontent.com/129182/82464654-9e636800-9abe-11ea-8946-ab7e525469f1.jpg)

# Filmweb.pl API 
Non-official API for Filmweb.pl

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg)](https://php.net/)
[![Latest Stable Version](https://img.shields.io/packagist/v/orkan/filmweb-api.svg)](https://packagist.org/packages/orkan/filmweb-api)
![API Methods progress](https://img.shields.io/endpoint?url=https://raw.githubusercontent.com/orkan/filmweb-api/badges/.badges/api_methods/badge.json)

* Highly configurable via an external configuration file
* Advanced PHP error handling
* Supports PHP CLI mode

## Installation
`$ composer require orkan/filmweb-api`

## Basic Usage
```php
<?php
use Orkan\Filmweb\Filmweb;
use Orkan\Filmweb\Api\Method\isLoggedUser;

// Login to Filmweb
$filmweb = new Filmweb( $login, $password );
$api = $filmweb->getApi();

// Get user info
$api->call( 'isLoggedUser' );
$user = $api->getData();
$userId = $user[ isLoggedUser::USERID ];

// Get list of voted films
$api->call( 'getUserFilmVotes', array( $userId ) );
$films = $api->getData();

// ...

print_r( $films );
```

## Third Party Packages
* [Monolog](https://github.com/Seldaek/monolog) for extended logging
* [Pimple](https://pimple.symfony.com) for PHP Dependency Injection

## About
### Requirements
This API library works with PHP 7.2 or above

### Author
Orkan - orkans@gmail.com - https://github.com/orkan

### License
This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

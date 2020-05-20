![repository-open-graph-template6d](https://user-images.githubusercontent.com/129182/81674511-9ddb2980-944d-11ea-9b5b-52ebef355ef7.jpg)

# Filmweb.pl API 
Non-official API for Filmweb.pl

[![Latest Stable Version](https://img.shields.io/packagist/v/orkan/filmweb-api.svg?style=flat-square)](https://packagist.org/packages/orkan/filmweb-api)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg?style=flat-square)](https://php.net/)

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
$user = $api->getData( 'array' );
$userId = $user[ isLoggedUser::USERID ];

// Get list of voted films
$api->call( 'getUserFilmVotes', array( $userId ) );
$films = $api->getData( 'array' );

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
